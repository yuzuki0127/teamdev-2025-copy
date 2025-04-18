<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_responses_id',
        'survey_question_id',
        'survey_category_id',
        'answer',
        'detail_answer',
        'solution_plan',
    ];

    /**
     * この回答は所属するレスポンスに属します。
     */
    public function surveyResponse()
    {
        return $this->belongsTo(SurveyResponse::class, 'survey_responses_id');
    }

    /**
     * この回答は所属する質問に属します。
     */
    public function surveyQuestion()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }
}
