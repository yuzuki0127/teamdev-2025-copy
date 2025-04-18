<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'period_title',
        'action_start_date',
        'action_end_date',
        'is_completed',
    ];

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function actionPeriodDetails()
    {
        return $this->hasMany(ActionPeriodDetail::class);
    }
}
