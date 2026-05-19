<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'cep',
        'phone',
        'rm',
        'school_class_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'guardian_student')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function authorizations()
    {
        return $this->hasMany(Authorization::class);
    }

    public function movements()
    {
        return $this->hasMany(StudentMovement::class);
    }
}
