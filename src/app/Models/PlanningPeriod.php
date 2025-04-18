<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningPeriod extends Model
{
    use HasFactory;
    
    protected $fillable = ['planning_detail_id', 'planning_title', 'planning_period'];

    public function planningDetail()
    {
        return $this->belongsTo(PlanningDetail::class);
    }

    public function planningPeriodDetails()
    {
        return $this->hasMany(PlanningPeriodDetail::class);
    }
}
