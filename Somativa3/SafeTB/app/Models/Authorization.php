<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{
    protected $fillable = [
        'student_id',
        'school_class_id',
        'course_id',
        'teacher_id',
        'created_by',
        'type',
        'status',
        'authorization_date',
        'scheduled_time',
        'real_time',
        'reason',
        'has_absence',
        'absence_count',
        'teacher_validated_at',
        'teacher_validated_by',
        'gate_validated_at',
        'gate_validated_by',
        'canceled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'authorization_date' => 'date',
        'real_time' => 'datetime',
        'teacher_validated_at' => 'datetime',
        'gate_validated_at' => 'datetime',
        'canceled_at' => 'datetime',
        'has_absence' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teacherValidatedBy()
    {
        return $this->belongsTo(User::class, 'teacher_validated_by');
    }

    public function gateValidatedBy()
    {
        return $this->belongsTo(User::class, 'gate_validated_by');
    }

    public function lessons()
    {
        return $this->hasMany(AuthorizationLesson::class);
    }

    public function movement()
    {
        return $this->hasOne(StudentMovement::class);
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class);
    }
}
