<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'survey_category_name',
    ];

    /**
     * このカテゴリは所属するアンケートに属します。
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * このカテゴリには複数の質問が存在します。
     */
    public function surveyQuestions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }
}
