<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPeriodDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_period_id',
        'action_detail_title',
        'action_detail_start_date',
        'action_detail_end_date',
        'action_detail_manager',
    ];

    public function actionPeriod()
    {
        return $this->belongsTo(ActionPeriod::class);
    }
}
