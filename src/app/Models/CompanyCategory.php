<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_category_name',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
