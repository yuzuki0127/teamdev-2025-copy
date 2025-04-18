<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionBasicTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_category_basic_template_id',
        'survey_question_text',
    ];

    /**
     * この質問が所属するカテゴリを取得
     */
    public function surveyCategoryBasicTemplate()
    {
        return $this->belongsTo(SurveyCategoryBasicTemplate::class);
    }
}
