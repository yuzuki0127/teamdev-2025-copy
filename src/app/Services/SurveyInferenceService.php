<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\Planning;
use App\Models\PlanningDetail;
use App\Models\PlanningPeriod;
use App\Models\PlanningPeriodDetail;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SurveyInferenceService
{
    // GPT推論時の補足文（必要に応じて設定）
    protected $detail_prompts = '特に、事前に実施したアンケート結果において、各質問の点数が低く、低評価が顕著な項目、または全体の平均評価が低い項目に注目し、そこから抽出される問題点を解決するための施策を中心に提案してください。';

    public function runInference(Survey $survey)
    {
        // 対象Surveyに対応するTeam情報を取得
        $team = Team::with(['companyCategory', 'industry'])->findOrFail($survey->team_id);

        $prompt = "
        以下のアンケート結果をもとに、{$team->team_name}（企業名：{$team->company_name}、会社区分：{$team->companyCategory->company_category_name}、業種：{$team->industry->industry_name}）が直面している重大な課題を抽出し、企業全体にとってクリティカルな改善策を3～5件、具体的かつ実行可能な施策として提案してください。
        {$this->detail_prompts}
        【アンケート結果】
        " . json_encode($survey->toArray()) . "
        【全体の構造】
        {
          'survey': {
            'survey_summary': '今回のアンケート結果の概要(日本語で100文字程度)',
          },
          'planning': {
            'planning_name': '大枠のプロジェクト名',
            'field': '分野（例：人事制度、職場環境、顧客満足向上など）',
            'planning_details': [
              {
                'title': '施策名',
                'description': '施策の概要（日本語で100文字程度）',
                'background': '施策の背景と現状の課題（日本語で100文字程度）',
                'purpose': '施策導入の目的（日本語で100文字程度）',
                'cost': '高/中/低',
                'priority': '高/中/低',
                'cost_detail': '導入コストの根拠',
                'priority_detail': '優先度を決定した理由',
                'process_of_reasoning': 'この施策を選定した根拠。アンケート結果で特に低評価だった質問やカテゴリを具体的に記載し、なぜ選んだ施策がクリティカルであるのか、また選んだ施策がどのように課題の解決に結びつくかを明記してください。',
                'planning_periods': [
                  {
                    'planning_title': '実施施策のフェーズ名(1週間単位で設定week◯)',
                    'planning_period': '実施期間（例：○日目～○日目）',
                    'planning_period_details': [
                      {
                        'planning_detail_title': 'フェーズ詳細（例：Week○の詳細なスケジュール）',
                        'planning_detail_period': '期間（例：Week○）',
                        'planning_detail_manager': '担当者名'
                      }
                    ]
                  }
                ]
              }
            ]
          }
        }
        
        【追加要件】
        - survey_summaryは定量データ（5択回答）と定性データ（自由記述）の両面から分析し、組織の弱み、全体傾向について200字程度で具体的なサマリーを生成してください。
        - 提案する各施策は、企業の現状の低評価部分を重点的に改善するものであること。
        - 各施策には、導入コスト、優先度、具体的な導入スケジュールを明示してください。
        - process_of_reasoning には、どのアンケート項目が低評価であったか、どのように施策がその問題を解決するか、具体的な根拠を記載してください。
        - plannning_detail_titleにはスケジュールの他に具体的なタスクに落とし込んでください。また、タスクは大きい項目のタスク(親タスク)とそのタスクをよりより細分化したタスク(小タスク)を作成してください。
        - 回答は日本語かつ有効な JSON 形式で、特殊文字は使用しないでください。
        ";

        // GPT API へのリクエスト
        $apiKey = env('OPENAI_API_KEY');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])
        ->timeout(30000)
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'o3-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ]
            ],
            'max_completion_tokens' => 7500,
        ]);

        $result = $response->json();

        if (isset($result['error'])) {
            throw new \Exception('API エラー: ' . $result['error']['message']);
        }

        $gptOutput = $result['choices'][0]['message']['content'];
        $data = json_decode($gptOutput, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decode error: ' . json_last_error_msg());
        }

        // トランザクション内でDB更新
        DB::beginTransaction();
        try {
            // Survey のサマリー更新
            $survey->survey_summary = $data['survey']['survey_summary'];
            $survey->save();

            // Planning 登録
            $planning = Planning::create([
                'team_id'       => $survey->team_id,
                'survey_id'     => $survey->id,
                'planning_name' => $data['planning']['planning_name'],
                'field'         => $data['planning']['field'],
            ]);

            // PlanningDetail などの登録処理
            foreach ($data['planning']['planning_details'] as $detail) {
                $planningDetail = PlanningDetail::create([
                    'planning_id'         => $planning->id,
                    'title'               => $detail['title'],
                    'description'         => $detail['description'],
                    'background'          => $detail['background'],
                    'purpose'             => $detail['purpose'],
                    'cost'                => $detail['cost'],
                    'priority'            => $detail['priority'],
                    'cost_detail'         => $detail['cost_detail'],
                    'priority_detail'     => $detail['priority_detail'],
                    'process_of_reasoning'=> $detail['process_of_reasoning'],
                ]);

                if (isset($detail['planning_periods']) && is_array($detail['planning_periods'])) {
                    foreach ($detail['planning_periods'] as $period) {
                        $planningPeriod = PlanningPeriod::create([
                            'planning_detail_id' => $planningDetail->id,
                            'planning_title'     => $period['planning_title'],
                            'planning_period'    => $period['planning_period'],
                        ]);

                        if (isset($period['planning_period_details']) && is_array($period['planning_period_details'])) {
                            foreach ($period['planning_period_details'] as $periodDetail) {
                                PlanningPeriodDetail::create([
                                    'planning_period_id'     => $planningPeriod->id,
                                    'planning_detail_title'  => $periodDetail['planning_detail_title'],
                                    'planning_detail_period' => $periodDetail['planning_detail_period'],
                                    'planning_detail_manager'=> $periodDetail['planning_detail_manager'],
                                ]);
                            }
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('保存中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}
