<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMovement extends Model
{
    protected $fillable = [
        'authorization_id',
        'student_id',
        'type',
        'occurred_at',
        'validated_by',
        'notes',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function authorization()
    {
        return $this->belongsTo(Authorization::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
