<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cpf',
        'relationship',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'guardian_student')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class);
    }
}
