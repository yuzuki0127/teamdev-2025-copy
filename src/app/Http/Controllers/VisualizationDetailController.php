<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyCategory;

class VisualizationDetailController extends Controller
{
    public function situationDetail(Request $request)
    {
        // ★ 全体平均の推移を集計する処理 ★
        $trend = DB::table('surveys')
            ->join('survey_responses', 'surveys.id', '=', 'survey_responses.survey_id')
            ->join('survey_answers', 'survey_responses.id', '=', 'survey_answers.survey_responses_id')
            ->select(
                'surveys.id as survey_id',
                'surveys.created_at',
                DB::raw('AVG(survey_answers.answer) as average')
            )
            ->groupBy('surveys.id', 'surveys.created_at')
            ->orderBy('surveys.created_at', 'asc')
            ->get();

        // 全体平均のラベル： created_at と survey_id を利用してユニークなラベルを作成
        $lineLabels = $trend->map(function ($item) {
            return date('Y-m-d', strtotime($item->created_at)) . " (#{$item->survey_id})";
        })->toArray();

        // 全体平均値
        $lineData = $trend->pluck('average')->map(function ($value) {
            return round($value, 2);
        })->toArray();

        // ★ 最新ラウンドの Survey と直前の Survey を取得
        $latestSurvey = Survey::orderBy('created_at', 'desc')->first();
        $previousSurvey = Survey::orderBy('created_at', 'desc')->skip(1)->first();

        // ★ 最新ラウンドの回答済みカテゴリID（重複排除）
        $latestCategoryIds = DB::table('survey_answers')
            ->join('survey_responses', 'survey_answers.survey_responses_id', '=', 'survey_responses.id')
            ->whereDate('survey_responses.created_at', $latestSurvey->created_at)
            ->pluck('survey_answers.survey_category_id')
            ->unique()
            ->toArray();

        // 最新ラウンドの項目数が16件未満の場合、初期値として不足する項目IDを追加
        if (count($latestCategoryIds) < 16) {
            $existing = $latestCategoryIds;
            for ($i = 1; $i <= 16; $i++) {
                if (!in_array($i, $existing)) {
                    $latestCategoryIds[] = $i;
                }
            }
            $latestCategoryIds = array_slice(array_unique($latestCategoryIds), 0, 16);
        } else {
            sort($latestCategoryIds);
            $latestCategoryIds = array_slice($latestCategoryIds, 0, 16);
        }

        // ★ すべてのアンケートに対するカテゴリごとの推移を集計
        $results = DB::table('survey_answers')
            ->join('survey_responses', 'survey_answers.survey_responses_id', '=', 'survey_responses.id')
            ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
            ->whereIn('survey_answers.survey_category_id', $latestCategoryIds)
            ->select(
                DB::raw('CONCAT(DATE_FORMAT(surveys.created_at, "%Y-%m-%d"), " (#", surveys.id, ")") as round_label'),
                'survey_answers.survey_category_id',
                DB::raw('AVG(survey_answers.answer) as average')
            )
            ->groupBy('surveys.id', 'survey_answers.survey_category_id', 'surveys.created_at')
            ->orderBy('surveys.created_at', 'asc')
            ->get();

        // 各カテゴリごとに、各 Survey 単位のデータを $data に格納
        $data = [];
        foreach ($results as $result) {
            $catId = $result->survey_category_id;
            if (!isset($data[$catId])) {
                $data[$catId] = [
                    'dates'    => [],
                    'averages' => [],
                ];
            }
            $data[$catId]['dates'][] = $result->round_label;
            $data[$catId]['averages'][] = round($result->average, 2);
        }

        // ★ 各カテゴリごとの最新値と前回比（最新の値と直前の値の差を計算）
        $itemStats = [];
        foreach ($latestCategoryIds as $categoryId) {
            if (!isset($data[$categoryId])) {
                $itemStats[$categoryId] = [
                    'average'  => 0,
                    'diff'     => 'N/A',
                    'dates'    => [],
                    'averages' => [],
                ];
            } else {
                $dates    = $data[$categoryId]['dates'];
                $averages = $data[$categoryId]['averages'];

                // 最新の平均値は、配列の最後の要素（既に正規化済み）
                $latestAverage = count($averages) > 0 ? $averages[count($averages) - 1] : 0;

                // 配列の要素が2件以上ある場合、最後から2番目の値との差を計算
                if (count($averages) > 1) {
                    $prevAverage = $averages[count($averages) - 2];
                    $diff = round($latestAverage - $prevAverage, 2);
                } else {
                    $diff = 'N/A';
                }

                $itemStats[$categoryId] = [
                    'average'  => $latestAverage,
                    'diff'     => $diff,
                    'dates'    => $dates,
                    'averages' => $averages,
                ];
            }
        }

        // ★ 各質問ごとの推移を集計（カテゴリごとにグループ化）
        $questionResults = DB::table('survey_answers')
            ->join('survey_responses', 'survey_answers.survey_responses_id', '=', 'survey_responses.id')
            ->join('survey_questions', 'survey_answers.survey_question_id', '=', 'survey_questions.id')
            ->whereIn('survey_answers.survey_category_id', $latestCategoryIds)
            ->select(
                DB::raw('CONCAT(DATE_FORMAT(surveys.created_at, "%Y-%m-%d"), " (#", surveys.id, ")") as round_label'),
                'survey_answers.survey_category_id',
                'survey_answers.survey_question_id',
                'survey_questions.survey_question_text',
                DB::raw('AVG(survey_answers.answer) as average')
            )
            ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
            ->groupBy('surveys.id', 'survey_answers.survey_category_id', 'survey_answers.survey_question_id', 'survey_questions.survey_question_text', 'surveys.created_at')
            ->orderBy('surveys.created_at', 'asc')
            ->get();

        $questionData = [];
        foreach ($questionResults as $result) {
            $catId = $result->survey_category_id;
            $questionId = $result->survey_question_id;
            if (!isset($questionData[$catId])) {
                $questionData[$catId] = [];
            }
            if (!isset($questionData[$catId][$questionId])) {
                $questionData[$catId][$questionId] = [
                    'question_text' => $result->survey_question_text,
                    'dates'         => [],
                    'averages'      => [],
                ];
            }
            $questionData[$catId][$questionId]['dates'][] = $result->round_label;
            $questionData[$catId][$questionId]['averages'][] = round($result->average, 2);
        }

        // ★ コントローラー側で、最新ラウンドの各質問の詳細な回答と解決策プランを取得
        $latestQuestionDetails = DB::table('survey_answers')
            ->join('survey_responses', 'survey_answers.survey_responses_id', '=', 'survey_responses.id')
            ->whereDate('survey_responses.created_at', $latestSurvey->created_at)
            ->select('survey_question_id', 'detail_answer', 'solution_plan', 'survey_answers.created_at')
            ->orderBy('survey_answers.created_at', 'desc')
            ->get()
            ->unique('survey_question_id')
            ->keyBy('survey_question_id');

        return view('visualization.situation-detail', compact(
            'lineLabels', 
            'lineData', 
            'itemStats', 
            'questionData',
            'latestQuestionDetails'
        ));
    }
}
