<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'survey_category_template_id',
        'survey_question_text',
        'editable',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function surveyCategoryTemplate()
    {
        return $this->belongsTo(SurveyCategoryTemplate::class);
    }
}
