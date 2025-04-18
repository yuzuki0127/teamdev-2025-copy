<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Survey;
use App\Models\Planning;
use Dflydev\DotAccessData\Data;

class AnalysisController extends Controller
{
    public $detail_prompts = '特に、事前に実施したアンケート結果において、各質問の評価が-2または-1といった低評価が顕著な項目、または全体の平均評価が低い項目に注目し、そこから抽出される問題点を解決するための施策を中心にしてください。';

    public function index()
    {
        $teamId = User::current()->team_id;
        $detail_prompts = $this->detail_prompts;
        // 最新アンケートと関連情報を一括取得
        $latestSurvey = Survey::with([
            'surveyCategories',
            'surveyResponses.surveyAnswers'
        ])
            ->where('team_id', $teamId)
            ->where('response_deadline', '<', Carbon::now())
            ->orderByDesc('created_at')
            ->first();

        if (!$latestSurvey) {
            $data = null;
            $plannings = null;
            return view('analysis.index', compact('latestSurvey', 'data', 'plannings', 'detail_prompts'))->with('error', '過去のアンケートが見つかりません。または、アンケートの回答期限が過ぎていません。');
        }

        $latestAveragesMapping = $latestSurvey->surveyCategories->mapWithKeys(function ($category) use ($latestSurvey) {
            $answers = $latestSurvey->surveyResponses->flatMap(function ($response) use ($category) {
                return $response->surveyAnswers->where('survey_category_id', $category->id);
            });
            return [$category->survey_category_name => round($answers->avg('answer'), 2)];
        })->toArray();

        $data = $latestSurvey->surveyCategories->map(function ($category) use ($latestAveragesMapping) {
            return $latestAveragesMapping[$category->survey_category_name] ?? 0;
        })->toArray();

        // 施策立案表示
        $plannings = Planning::with([
            'planningDetails.planningPeriods.planningPeriodDetails'
        ])
            ->where('team_id', auth()->user()->team_id)
            ->orderByDesc('created_at')
            ->first();

        return view('analysis.index', compact(
          // グラフ用
          'latestSurvey',
          'data',
          // 施策立案表示用
          'plannings',
          'detail_prompts'
        ));
    }

    public function detail(Request $request)
    {
        // 最新のアンケート（最も新しい Survey）と直前のアンケートを取得
        $user = User::current();
        $teamId = $user->team_id;
        $surveys = Survey::with(['surveyCategories.surveyQuestions','surveyResponses.surveyAnswers'])
            ->where('team_id', $teamId)
            ->where('response_deadline', '<', Carbon::now())
            ->orderByDesc('created_at')
            ->get();

        $latestSurvey = $surveys->first();
        $previousSurvey = $surveys->skip(1)->first();

        if (!$latestSurvey) {
          return view('analysis.detail', [
              'latestSurvey'  => null,
              'itemStats'     => [],
              'data'          => [],
              'lineLabels'    => [],
              'lineData'      => [],
              'mostIncreased' => null,
              'mostDecreased' => null,
              'questionData'  => [],
          ]);
      }

        $latestCategories = $latestSurvey->surveyCategories()->orderBy('id')->get();

        // 直近のアンケート平均
        $latestAveragesMapping = $latestCategories->mapWithKeys(function ($category) use ($latestSurvey) {
            $answers = $latestSurvey->surveyResponses->flatMap(function ($response) use ($category) {
                return $response->surveyAnswers->where('survey_category_id', $category->id);
            });
            return [$category->survey_category_name => round($answers->avg('answer'), 2)];
        })->toArray();

        // 前回のアンケート平均
        $prevAveragesMapping = [];
        if ($previousSurvey) {
            $prevCategories = $previousSurvey->surveyCategories()->orderBy('id')->get();
            $prevAveragesMapping = $prevCategories->mapWithKeys(function ($category) use ($previousSurvey) {
                $answers = $previousSurvey->surveyResponses->flatMap(function ($response) use ($category) {
                    return $response->surveyAnswers->where('survey_category_id', $category->id);
                });
                return [$category->survey_category_name => round($answers->avg('answer'), 2)];
            })->toArray();
        }

        // 各カテゴリごとの平均と前回比を作成（キーはカテゴリ名）
        $itemStats = [];
        foreach ($latestCategories as $category) {
            $name = $category->survey_category_name;
            $latestValue = $latestAveragesMapping[$name] ?? null;
            $prevValue   = $prevAveragesMapping[$name] ?? null;
            if ($latestValue !== null && $prevValue !== null) {
                $rawDiff = round($latestValue - $prevValue, 2);
                $diff = ($rawDiff >= 0 ? '+' . $rawDiff : (string)$rawDiff);
            } else {
                $diff = null;
            }
            $questionAverages = $category->surveyQuestions->mapWithKeys(function ($question) use ($latestSurvey) {
                $answers = $latestSurvey->surveyResponses->flatMap(function ($response) use ($question) {
                    return $response->surveyAnswers->where('survey_question_id', $question->id);
                });
                return [$question->id => round($answers->avg('answer'), 2)];
            })->toArray();

            $itemStats[$name] = [
                'average'  => $latestValue,
                'diff'     => $diff,
                'averages' => $questionAverages,
            ];
        }

        // 変化量が最大／最小のカテゴリを算出（diff が存在するもののみ）
        $mostIncreased = null;
        $mostDecreased = null;
        $differences = [];
        foreach ($latestCategories as $category) {
            $name = $category->survey_category_name;
            $latestValue = isset($latestAveragesMapping[$name]) ? $latestAveragesMapping[$name] : null;
            $prevValue = isset($prevAveragesMapping[$name]) ? $prevAveragesMapping[$name] : null;
            $diff = ($latestValue !== null && $prevValue !== null)
                ? round($latestValue - $prevValue, 2)
                : null;
            if ($diff !== null) {
                $differences[$name] = $diff;
            }
        }

        if (!empty($differences)) {
            $maxDiff = max($differences);
            $minDiff = min($differences);
            $mostIncreasedCategory = array_keys($differences, $maxDiff)[0];
            $mostDecreasedCategory = array_keys($differences, $minDiff)[0];
            $mostIncreasedDiff = ($maxDiff >= 0 ? '+' . $maxDiff : (string)$maxDiff);
            $mostDecreasedDiff = ($minDiff >= 0 ? '+' . $minDiff : (string)$minDiff);
            $mostIncreased = [
                'categoryName' => $mostIncreasedCategory,
                'diff' => $mostIncreasedDiff
            ];
            $mostDecreased = [
                'categoryName' => $mostDecreasedCategory,
                'diff' => $mostDecreasedDiff
            ];
        }

        // レーダーチャートに表示するデータを作成
        $data = [];
        foreach ($latestCategories as $category) {
            $data[] = isset($latestAveragesMapping[$category->survey_category_name])
                ? $latestAveragesMapping[$category->survey_category_name]
                : 0;
        }

        // ★ 全体平均推移（折れ線グラフ用）のデータ
        $trend = $surveys->reverse()->map(function ($survey) {
          $average = $survey->surveyResponses->flatMap(function ($response) {
              return $response->surveyAnswers;
          })->avg('answer');
          return [
              'survey_id' => $survey->id,
              'created_at' => $survey->created_at,
              'average' => round($average, 2),
          ];
      });
      $lineLabels = $trend->pluck('created_at')->map(function ($date) {
          return date('Y-m-d', strtotime($date));
      })->toArray();
      $lineData = $trend->pluck('average')->toArray();

        // 各カテゴリごとの質問データを取得
        $questionData = [];
        foreach ($latestCategories as $category) {
            $catId = $category->id;
            $questions = DB::table('survey_questions')
                ->join('survey_answers', 'survey_answers.survey_question_id', '=', 'survey_questions.id')
                ->where('survey_answers.survey_category_id', $catId)
                ->select('survey_questions.id as question_id', 'survey_questions.survey_question_text', DB::raw('AVG(survey_answers.answer) as average'))
                ->groupBy('survey_questions.id')
                ->get();

            foreach ($questions as $question) {
                $questionData[$catId][$question->question_id] = [
                    'question_text' => $question->survey_question_text,
                    'averages' => [round($question->average, 2)],
                    'dates' => [$latestSurvey->created_at],
                ];
            }
        }

        // それぞれのデータをビューに渡す
        return view('analysis.detail', [
            'latestSurvey'   => $latestSurvey, 
            'itemStats'      => $itemStats,
            'data'           => $data,
            'lineLabels'     => $lineLabels,
            'lineData'       => $lineData,
            'mostIncreased'  => $mostIncreased,
            'mostDecreased'  => $mostDecreased,
            'questionData'   => $questionData,
            // 'labels'         => $labels,
          ]);
    }

    // 選択した詳細に移動する
    public function select(Request $request)
    {
        $detailId = $request->input('planning_detail_id');

        $selected = session()->get('selected_planning_details', []);
        if (!in_array($detailId, $selected)) {
            $selected[] = $detailId;
            session()->put('selected_planning_details', $selected);
        }

        return redirect()->route('action.create')->with('status', '選択しました。');
    }

    // 過去の立案を表示する
    public function past()
    {
        $plannings = Planning::with([
            'planningDetails.planningPeriods.planningPeriodDetails'
        ])
            ->where('team_id', auth()->user()->team_id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('analysis.past', compact('plannings'));
    }
}
