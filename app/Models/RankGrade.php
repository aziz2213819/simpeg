<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankGrade extends Model
{
    protected $fillable = [
        'grade_code',
        'rank_name'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
