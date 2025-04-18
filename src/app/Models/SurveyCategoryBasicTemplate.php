<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyCategoryBasicTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_category_name',
    ];

    /**
     * このカテゴリに紐づく複数の質問を取得
     */
    public function surveyQuestionBasicTemplates()
    {
        return $this->hasMany(SurveyQuestionBasicTemplate::class);
    }
}
