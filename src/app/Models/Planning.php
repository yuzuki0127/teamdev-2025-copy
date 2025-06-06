<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;
    
    protected $fillable = ['team_id', 'survey_id', 'planning_name', 'field'];

    public function planningDetails()
    {
        return $this->hasMany(PlanningDetail::class);
    }
}
