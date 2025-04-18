<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SurveyRequestMail;
use App\Models\User;
use App\Models\Team;
use App\Models\Employee;
use App\Models\SurveyCategoryTemplate;
use App\Models\SurveyQuestionTemplate;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyResponse;

class SurveyController extends Controller
{
    public function create()
    {
        $user = User::current();
        $teamId = $user->team_id;
        $team = Team::with('employees')->where('id', $teamId)->first();
        $templates = SurveyCategoryTemplate::with('surveyQuestionTemplates')->where('team_id', $teamId)->get();
        return view('survey.create', compact('templates', 'team'));
    }

    public function storeSurvey(Request $request)
    {
        $user = User::current();
        $teamId = $user->team_id;

        // 質問テンプレートの削除処理
        SurveyQuestionTemplate::where('editable', 1)
            ->where('team_id', $teamId)
            ->delete();

        // 新しい質問を保存
        foreach ($request->input('question_texts', []) as $categoryId => $questions) {
            foreach ($questions as $questionText) {
                if (!empty($questionText)) {
                    SurveyQuestionTemplate::create([
                        'survey_category_template_id' => $categoryId,
                        'survey_question_text' => $questionText,
                        'team_id' => $teamId,
                        'editable' => true,
                    ]);
                }
            }
        }

        return redirect()->route('survey.create')->with('status', 'アンケートを保存しました！');
    }

    public function sendSurveyEmail(Request $request)
    {
        $user = User::current();
        $team = Team::where('id', $user->team_id)->first();
        $teamId = $user->team_id;
        $employees = Employee::where('team_id', $teamId)->get();
        $countSurvey = Survey::where('team_id', $teamId)->count() + 1;
        $questions = SurveyQuestionTemplate::where('team_id', $teamId)->count();
        $totalSeconds = $questions * 30;
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;
        $result = $minutes . '分' . $seconds . '秒';

        DB::beginTransaction();
        try {
            $survey = Survey::create([
                'team_id' => $teamId,
                'survey_name' => $team->team_name . ' 第' . ($countSurvey) . '回職場改善アンケート',
                'survey_description' => 'チーム' . $teamId . 'の' . ($countSurvey) . '回目のアンケートです',
                // nits ドメイン知識をmodelに移動
                'response_deadline' => now()->addDays(14)->startOfDay(),
                'survey_summary' => null,
            ]);

            $categories = SurveyCategoryTemplate::with('surveyQuestionTemplates')->where('team_id', $teamId)->get();
            foreach ($categories as $categoryTemplate) {
                $category = $survey->surveyCategories()->create([
                    'survey_id' => $survey->id,
                    'survey_category_name' => $categoryTemplate->survey_category_name,
                ]);
                foreach ($categoryTemplate->surveyQuestionTemplates as $questionTemplate) {
                    $category->surveyQuestions()->create([
                        'survey_id' => $survey->id,
                        'survey_category_id' => $category->id,
                        'survey_question_text' => $questionTemplate->survey_question_text,
                    ]);
                }
            }
            DB::commit();

            // メール送信処理
            foreach ($employees as $employee) {
                Mail::to($employee->email)->send(new SurveyRequestMail($user, $employee, $countSurvey, $survey, $result));
            }
            return redirect()->route('dashboard')->with('status', 'アンケートのメールが送信されました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('survey.create')->with('error', 'アンケートのメールの送信に失敗しました。');
        }
    }

    //アンケート回答
    public function survey($surveyId, $employeeId)
    {
        $surveys = Survey::with('surveyQuestions')->where('id', $surveyId)->first();

        $countSurveyQuestions = $surveys->surveyQuestions->count();
        $totalSeconds = $countSurveyQuestions * 30;
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;
        $result = $minutes . '分' . $seconds . '秒';


        //nits　$labelsと$circleSizesをモデルに書きたい
        $ratings = [
          -2 => [
              'label' => '思わない',
              'circleSize' => '4rem'
          ],
          -1 => [
              'label' => 'あまり思わない',
              'circleSize' => '3rem'
          ],
          0 => [
              'label' => 'どちらともいえない',
              'circleSize' => '2rem'
          ],
          1 => [
              'label' => 'やや思う',
              'circleSize' => '3rem'
          ],
          2 => [
              'label' => 'そう思う',
              'circleSize' => '4rem'
          ]
      ];

        return view('survey.survey', compact('surveys', 'ratings', 'employeeId', 'result'));
    }

    // 回答を保存
    public function storeAnswers(Request $request, $surveyId, $employeeId)
    {
        $employee = Employee::where('id', $employeeId)->first();

        $request->validate([
            'answers' => 'required|array',
            'answers.*.answer' => 'required|integer|between:-2,2',
            'answers.*.detail_answer' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 新しいSurveyResponseを作成
            $response = SurveyResponse::create([
                'survey_id' => $surveyId,
                'sex' => $employee->sex,
                'year_of_birth' => $employee->year_of_birth,
            ]);

            // 各質問に対する回答を保存
            foreach ($request->answers as $answerData) {
                SurveyAnswer::create([
                    'survey_responses_id' => $response->id,
                    'survey_question_id' => $answerData['survey_question_id'],
                    'survey_category_id' => $answerData['survey_category_id'],
                    'answer' => $answerData['answer'],
                    'detail_answer' => $answerData['detail_answer'] ?? null,
                ]);
            }
            DB::commit();

            return redirect()->route('survey.thanks');
        } catch (\Exception $e) {
            DB::rollBack();

            // ユーザーにエラーメッセージを表示
            return back()->with(['error' => '回答の保存中にエラーが発生しました。もう一度お試しください。']);
        }
    }
}
