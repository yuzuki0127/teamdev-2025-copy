<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\SurveyCategoryBasicTemplate;
use App\Models\SurveyQuestionBasicTemplate;
use App\Models\SurveyCategoryTemplate;
use App\Models\SurveyQuestionTemplate;

class SurveyTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // ① 基本テンプレート（basic_template）に登録するデータ
        $categoriesData = [
          [
              'name' => '顧客基盤の安定性',
              'questions' => [
                  '当社が顧客との長期的な関係を維持するための取り組みに満足していますか？',
              ],
          ],
          [
              'name' => '理念戦略への納得感',
              'questions' => [
                  'あなたは当社のビジョンや戦略に対して、どの程度納得していますか？',
              ],
          ],
          [
              'name' => '社会的貢献',
              'questions' => [
                  'あなたは当社の社会的貢献の取り組み（環境保護・ボランティア・寄付など）にどの程度満足していますか？',
              ],
          ],
          [
              'name' => '責任と顧客・社会への貢献',
              'questions' => [
                  '当社は企業としての責任（コンプライアンス・倫理的行動など）を果たしていると感じますか？',
              ],
          ],
          [
              'name' => '連帯感と相互尊重',
              'questions' => [
                  'チーム内でのコミュニケーションの取りやすさにどの程度満足していますか？',
              ],
          ],
          [
              'name' => '魅力的な上司・同僚',
              'questions' => [
                  'あなたは、上司のリーダーシップや指導に満足していますか？',
              ],
          ],
          [
              'name' => '勤務地や会社設備の魅力',
              'questions' => [
                  'あなたは、社内の設備や職場の環境（オフィスの快適さや働きやすさ）にどの程度満足していますか？',
              ],
          ],
          [
              'name' => '評価・給与と柔軟な働き方',
              'questions' => [
                  'あなたは、勤務時間や働き方に柔軟性があり、自身のライフスタイルに合った働き方ができていると感じますか？',
              ],
          ],
          [
              'name' => '顧客ニーズや事業戦略の伝達',
              'questions' => [
                  '経営陣が発信する企業の方向性や顧客の期待について、十分な情報を得られていますか？',
              ],
          ],
          [
              'name' => '上司や会社からの理解',
              'questions' => [
                  '職場での上司や会社の理解・サポートの姿勢について、どの程度満足していますか？',
              ],
          ],
          [
              'name' => '公平な評価',
              'questions' => [
                  'あなたの業績や行動が、公正に評価されていると感じ、その評価に満足していますか？',
              ],
          ],
          [
              'name' => '上司からの適切な教育・支援',
              'questions' => [
                  'あなたの上司は業務に必要なスキルや知識の習得をサポートする環境を提供していますか？',
              ],
          ],
          [
              'name' => '顧客の期待を上回る提案',
              'questions' => [
                  '当社の内部で蓄積された知識や情報は、業務において適切に活用されていると感じますか？',
              ],
          ],
          [
              'name' => '具体的な目標の共有',
              'questions' => [
                  '会社の目標に関する情報は、上層部から適切に伝達され、従業員に共有されていると感じますか？',
              ],
          ],
          [
              'name' => '未来に向けた活動',
              'questions' => [
                  '企業が設定した将来のビジョンに対して、どの程度の信頼を持っていますか？',
              ],
          ],
          [
              'name' => 'ナレッジの標準化',
              'questions' => [
                  '企業内の知識や情報が整理され、効率的に活用できる状態になっていると感じていますか？',
              ],
          ],
      ];

        // ①-1. 基本テンプレートテーブルへデータ登録
        foreach ($categoriesData as $catData) {
            $basicCategory = SurveyCategoryBasicTemplate::create([
                'survey_category_name' => $catData['name'],
            ]);
            foreach ($catData['questions'] as $questionText) {
                SurveyQuestionBasicTemplate::create([
                    'survey_category_basic_template_id' => $basicCategory->id,
                    'survey_question_text'              => $questionText,
                ]);
            }
        }

        // Team1Dataの修正（質問を配列形式で格納）
        $Team1Data = [
          [
              'name' => '顧客基盤の安定性',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '当社が顧客との長期的な関係を維持するための取り組みに満足していますか？',
                      'editable' => 0,
                  ],
                  [
                      'survey_question_text' => '当社の顧客基盤の安定性（顧客の継続率や関係性の強さ）について、どの程度満足していますか？',
                      'editable' => 1,
                  ],
              ],
          ],
          [
              'name' => '理念戦略への納得感',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは当社のビジョンや戦略に対して、どの程度納得していますか？',
                      'editable' => 0,
                  ],
                  [
                      'survey_question_text' => '当社のビジョンや戦略に対する経営陣の説明や伝え方に満足していますか？',
                      'editable' => 1,
                  ],
              ],
          ],
          [
              'name' => '社会的貢献',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは当社の社会的貢献の取り組み（環境保護・ボランティア・寄付など）にどの程度満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '責任と顧客・社会への貢献',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '当社は企業としての責任（コンプライアンス・倫理的行動など）を果たしていると感じますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '連帯感と相互尊重',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'チーム内でのコミュニケーションの取りやすさにどの程度満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '魅力的な上司・同僚',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは、上司のリーダーシップや指導に満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '勤務地や会社設備の魅力',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは、社内の設備や職場の環境（オフィスの快適さや働きやすさ）にどの程度満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '評価・給与と柔軟な働き方',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは、勤務時間や働き方に柔軟性があり、自身のライフスタイルに合った働き方ができていると感じますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '顧客ニーズや事業戦略の伝達',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '経営陣が発信する企業の方向性や顧客の期待について、十分な情報を得られていますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '上司や会社からの理解',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '職場での上司や会社の理解・サポートの姿勢について、どの程度満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '公平な評価',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたの業績や行動が、公正に評価されていると感じ、その評価に満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '上司からの適切な教育・支援',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたの上司は業務に必要なスキルや知識の習得をサポートする環境を提供していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '顧客の期待を上回る提案',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '当社の内部で蓄積された知識や情報は、業務において適切に活用されていると感じますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '具体的な目標の共有',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '会社の目標に関する情報は、上層部から適切に伝達され、従業員に共有されていると感じますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '未来に向けた活動',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '企業が設定した将来のビジョンに対して、どの程度の信頼を持っていますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => 'ナレッジの標準化',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '企業内の知識や情報が整理され、効率的に活用できる状態になっていると感じていますか？',
                      'editable' => 0,
                  ],
              ],
          ],
      ];

        // Team2Dataの修正（質問を配列形式で格納）
        $Team2Data = [
          [
              'name' => '顧客基盤の安定性',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '当社が顧客との長期的な関係を維持するための取り組みに満足していますか？',
                      'editable' => 0,
                  ]
              ],
          ],
          [
              'name' => '理念戦略への納得感',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは当社のビジョンや戦略に対して、どの程度納得していますか？',
                      'editable' => 0,
                  ]
              ],
          ],
          [
              'name' => '社会的貢献',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは当社の社会的貢献の取り組み（環境保護・ボランティア・寄付など）にどの程度満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '責任と顧客・社会への貢献',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '当社は企業としての責任（コンプライアンス・倫理的行動など）を果たしていると感じますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '連帯感と相互尊重',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'チーム内でのコミュニケーションの取りやすさにどの程度満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '魅力的な上司・同僚',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは、上司のリーダーシップや指導に満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '勤務地や会社設備の魅力',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは、社内の設備や職場の環境（オフィスの快適さや働きやすさ）にどの程度満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '評価・給与と柔軟な働き方',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたは、勤務時間や働き方に柔軟性があり、自身のライフスタイルに合った働き方ができていると感じますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '顧客ニーズや事業戦略の伝達',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '経営陣が発信する企業の方向性や顧客の期待について、十分な情報を得られていますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '上司や会社からの理解',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '職場での上司や会社の理解・サポートの姿勢について、どの程度満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '公平な評価',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたの業績や行動が、公正に評価されていると感じ、その評価に満足していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '上司からの適切な教育・支援',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => 'あなたの上司は業務に必要なスキルや知識の習得をサポートする環境を提供していますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '顧客の期待を上回る提案',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '当社の内部で蓄積された知識や情報は、業務において適切に活用されていると感じますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '具体的な目標の共有',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '会社の目標に関する情報は、上層部から適切に伝達され、従業員に共有されていると感じますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => '未来に向けた活動',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '企業が設定した将来のビジョンに対して、どの程度の信頼を持っていますか？',
                      'editable' => 0,
                  ],
              ],
          ],
          [
              'name' => 'ナレッジの標準化',
              'editable' => 0,
              'questions' => [
                  [
                      'survey_question_text' => '企業内の知識や情報が整理され、効率的に活用できる状態になっていると感じていますか？',
                      'editable' => 0,
                  ],
              ],
          ],
      ];

        // Team1のデータ挿入
        foreach ($Team1Data as $catData) {
            // カテゴリの作成
            $templateCategory = SurveyCategoryTemplate::create([
                'team_id'              => 1,
                'survey_category_name' => $catData['name'],
                'editable'             => $catData['editable'],
            ]);
        
            // 質問の作成
            foreach ($catData['questions'] as $questionData) {
                SurveyQuestionTemplate::create([
                    'team_id'                        => 1,
                    'survey_category_template_id'    => $templateCategory->id,
                    'survey_question_text'           => $questionData['survey_question_text'],
                    'editable'                       => $questionData['editable'],
                ]);
            }
        }

        // Team2のデータ挿入
        foreach ($Team2Data as $catData) {
            // カテゴリの作成
            $templateCategory = SurveyCategoryTemplate::create([
                'team_id'              => 2,
                'survey_category_name' => $catData['name'],
                'editable'             => $catData['editable'],
            ]);
        
            // 質問の作成
            foreach ($catData['questions'] as $questionData) {
                SurveyQuestionTemplate::create([
                    'team_id'                        => 2,
                    'survey_category_template_id'    => $templateCategory->id,
                    'survey_question_text'           => $questionData['survey_question_text'],
                    'editable'                       => $questionData['editable'],
                ]);
            }
        }
    }
}
