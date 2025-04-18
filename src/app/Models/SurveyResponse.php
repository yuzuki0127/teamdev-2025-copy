<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'sex',
        'year_of_birth',
    ];

    /**
     * このレスポンスは所属するアンケートに属します。
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * このレスポンスには複数の回答が存在します。
     */
    public function surveyAnswers()
    {
        return $this->hasMany(SurveyAnswer::class, 'survey_responses_id', 'id');
    }
}
