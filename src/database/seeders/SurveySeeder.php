<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Team;
use App\Models\Employee;
use App\Models\SurveyCategoryTemplate;
use App\Models\SurveyQuestionTemplate;
use App\Models\Survey;
use App\Models\SurveyCategory;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        // チーム1のアンケートを作成
        $team1 = Team::find(1);

        // Team 1 - 5回のアンケート作成
        $survey1_1 = $this->createSurvey($team1, 1, '2023-01-15');
        $survey1_2 = $this->createSurvey($team1, 2, '2023-06-15');
        $survey1_3 = $this->createSurvey($team1, 3, '2024-01-15');
        $survey1_4 = $this->createSurvey($team1, 4, '2024-06-15');
        $survey1_5 = $this->createSurvey($team1, 5, Carbon::now()->addDays(14)->format('Y-m-d'));

        // SurveyCategory と SurveyQuestion の作成
        $this->createSurveyCategoriesAndQuestions($survey1_1);
        $this->createSurveyCategoriesAndQuestions($survey1_2);
        $this->createSurveyCategoriesAndQuestions($survey1_3);
        $this->createSurveyCategoriesAndQuestions($survey1_4);
        $this->createSurveyCategoriesAndQuestions($survey1_5);

        // Team 2 - 1回のアンケート作成
        $team2 = Team::find(2);
        $survey2_1 = Survey::create([
            'team_id'            => $team2->id,
            'survey_name'        => $team2->team_name . ' 第1回職場改善アンケート',
            'survey_description' => 'チーム' . $team2->id . 'の第1回目のアンケートです',
            'response_deadline'  => Carbon::create(2025, 3, 15, 0, 0, 0),
            'survey_summary'     => 'ここにはAIが生成した要約が表示されます',
            'created_at'         => Carbon::create(2025, 3, 1, 12, 0, 0),
            'updated_at'         => Carbon::create(2025, 3, 1, 12, 0, 0),
        ]);

        $this->createSurveyCategoriesAndQuestions($survey2_1);

        // SurveyResponse と SurveyAnswer の作成（全従業員の回答）
        $this->createSurveyResponsesAndAnswersForTeam1();
        $this->createSurveyResponsesAndAnswersForTeam2($survey2_1);
    }

    /**
     * Surveyを作成する
     *
     * @param Team $team
     * @param int $index
     * @param string $response_deadline
     * @return Survey
     */
    private function createSurvey(Team $team, int $index, string $response_deadline): Survey
    {
        return Survey::create([
            'team_id'            => $team->id,
            'survey_name'        => $team->team_name . " 第{$index}回職場改善アンケート",
            'survey_description' => "チーム{$team->id}の第{$index}回目のアンケートです",
            'response_deadline'  => Carbon::createFromFormat('Y-m-d', $response_deadline),
            'survey_summary'     => 'ここにはAIが生成した要約が表示されます',
            'created_at'         => Carbon::createFromFormat('Y-m-d', $response_deadline)->subMonths(5),
            'updated_at'         => Carbon::now(),
        ]);
    }

    /**
     * SurveyCategory と SurveyQuestion を作成する
     *
     * @param Survey $survey
     * @return void
     */
    private function createSurveyCategoriesAndQuestions(Survey $survey)
    {
        $categoriesData = SurveyCategoryTemplate::where('team_id', $survey->team_id)->get();

        foreach ($categoriesData as $categoryData) {
            $category = SurveyCategory::create([
                'survey_id'            => $survey->id,
                'survey_category_name' => $categoryData->survey_category_name,
            ]);

            // 質問を作成
            $questionsData = SurveyQuestionTemplate::where('survey_category_template_id', $categoryData->id)->get();
            foreach ($questionsData as $questionData) {
                SurveyQuestion::create([
                    'survey_id'            => $survey->id,
                    'survey_category_id'   => $category->id,
                    'survey_question_text' => $questionData->survey_question_text,
                ]);
            }
        }
    }

    /**
     * Team 1 の全アンケートに対して回答を作成する
     */
    private function createSurveyResponsesAndAnswersForTeam1()
    {
        $team1 = Team::find(1);
        $employees1 = Employee::where('team_id', $team1->id)->get();

        // Team 1 の 5 回のアンケートに対して回答を作成
        $surveys = Survey::where('team_id', $team1->id)->get();
        foreach ($surveys as $survey) {
            foreach ($employees1 as $employee) {
                $response = SurveyResponse::create([
                    'survey_id'     => $survey->id,
                    'sex'           => $employee->sex,
                    'year_of_birth' => $employee->year_of_birth,
                ]);

                // 質問に対する回答を作成
                $questions = SurveyQuestion::where('survey_id', $survey->id)->get();
                foreach ($questions as $question) {
                    SurveyAnswer::create([
                        'survey_responses_id' => $response->id,
                        'survey_question_id'  => $question->id,
                        'survey_category_id'  => $question->survey_category_id,
                        'answer'              => rand(-2, 2),
                        'detail_answer'       => rand(1, 2) === 1 ? '質問 ' . $question->id . ' に対する詳細な回答です。' : null,
                        'solution_plan'       => null,
                    ]);
                }
            }
        }
    }

    /**
     * Team 2 の 1 回のアンケートに対して回答を作成する
     *
     * @param Survey $survey
     */
    private function createSurveyResponsesAndAnswersForTeam2(Survey $survey)
    {
        $employees2 = Employee::where('team_id', $survey->team_id)->get();
        foreach ($employees2 as $employee) {
            $response = SurveyResponse::create([
                'survey_id'     => $survey->id,
                'sex'           => $employee->sex,
                'year_of_birth' => $employee->year_of_birth,
            ]);

            // 質問に対する回答を作成
            $questions = SurveyQuestion::where('survey_id', $survey->id)->get();
            foreach ($questions as $question) {
                SurveyAnswer::create([
                    'survey_responses_id' => $response->id,
                    'survey_question_id'  => $question->id,
                    'survey_category_id'  => $question->survey_category_id,
                    'answer'              => rand(-2, 2),  // ランダムに-2~2の範囲で回答
                    'detail_answer'       => rand(1, 2) === 1 ? '質問 ' . $question->id . ' に対する詳細な回答です。' : null,
                    'solution_plan'       => null,
                ]);
            }
        }
    }
}
