<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'title',
        'message',
        'is_read',
    ];

    public function employees()
    {
        return $this->belongsTo(Employee::class);
    }
}
