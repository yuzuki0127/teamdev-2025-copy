<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'title',
        'description',
        'background',
        'purpose',
        'deadline',
        'stopped',
        'is_completed',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function actionPeriods()
    {
        return $this->hasMany(ActionPeriod::class);
    }
}
