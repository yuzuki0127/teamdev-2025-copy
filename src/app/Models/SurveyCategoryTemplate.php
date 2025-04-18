<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyCategoryTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'survey_category_name',
        'editable',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function surveyQuestionTemplates()
    {
        return $this->hasMany(SurveyQuestionTemplate::class);
    }
}
