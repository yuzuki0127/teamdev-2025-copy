<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    const SEX_MALE = 1;
    const SEX_FEMALE = 0;

    protected $fillable = [
        'team_id',
        'employee_name',
        'email',
        'sex',
        'year_of_birth',
    ];

    protected $casts = [
        'sex' => 'boolean', // sex を true(1) / false(0) として扱う
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getSexLabelAttribute()
    {
        return $this->sex === self::SEX_MALE ? '男性' : '女性';
    }
}
