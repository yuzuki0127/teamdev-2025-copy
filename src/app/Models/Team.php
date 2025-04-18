<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'team_name',
        'company_category_id',
        'industry_id',
    ];

    public function companyCategory()
    {
        return $this->belongsTo(CompanyCategory::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function surveyCategoryTemplates()
    {
        return $this->hasMany(SurveyCategoryTemplate::class);
    }

    public function surveyQuestionTemplates()
    {
        return $this->hasMany(SurveyQuestionTemplate::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }
}
