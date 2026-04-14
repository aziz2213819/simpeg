<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nip',
        'name',
        'birth_date',
        'gender',
        'status',
        'tmt_start',
        'tmt_end',
        'type',
        'position_id',
        'grade_id',
        'rank_id',
    ];
    
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function notifications() 
    {
        return $this->hasMany(Notification::class);
    }
}
