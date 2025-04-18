<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningPeriodDetail extends Model
{
    use HasFactory;
    
    protected $fillable = ['planning_period_id', 'planning_detail_title', 'planning_detail_period', 'planning_detail_manager'];

    public function planningPeriod()
    {
        return $this->belongsTo(PlanningPeriod::class);
    }
}
