<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {

    use HasFactory,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all courses for the user (if they are a teacher).
     */
    public function courses() {
        return $this->hasMany(Course::class, 'teacherID', 'teacherID');
    }

    /**
     * Get the teacher profile associated with the user.
     */
    public function teacher() {
        return $this->hasOne(Teacher::class, 'user_id', 'id');
    }

    public function student() {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    /**
     * Scope a query to only include teachers.
     */
    public function scopeTeachers($query) {
        return $query->where('type', 'teacher');
    }
}
