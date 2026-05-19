<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonSchedule extends Model
{
    protected $fillable = [
        'school_class_id',
        'lesson_number',
        'start_time',
        'end_time',
        'duration_minutes',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
