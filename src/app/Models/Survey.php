<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'survey_name',
        'survey_description',
        'response_deadline',
        'survey_summary',
    ];

    /**
     * このアンケートは所属するチームに属します。
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function surveyCategories()
    {
        return $this->hasMany(SurveyCategory::class);
    }

    public function surveyQuestions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function surveyAnswers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
