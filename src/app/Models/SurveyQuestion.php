<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'survey_category_id',
        'survey_question_text',
    ];

    /**
     * この質問は所属するアンケートに属します。
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * この質問は所属するカテゴリに属します。
     */
    public function surveyCategories()
    {
        return $this->belongsTo(SurveyCategory::class);
    }

    /**
     * この質問は所属するアンケートに属する回答に属します。
     */
    public function surveyAnswers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
