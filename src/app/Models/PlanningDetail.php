<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'planning_id', 'title', 'description', 'background', 'purpose',
        'cost', 'priority', 'cost_detail', 'priority_detail', 'process_of_reasoning'
    ];

    public function planning()
    {
        return $this->belongsTo(Planning::class);
    }

    public function planningPeriods()
    {
        return $this->hasMany(PlanningPeriod::class);
    }
}
