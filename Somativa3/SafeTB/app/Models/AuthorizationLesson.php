<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizationLesson extends Model
{
    protected $fillable = [
        'authorization_id',
        'lesson_number',
        'start_time',
        'end_time',
        'status',
    ];

    public function authorization()
    {
        return $this->belongsTo(Authorization::class);
    }
}
