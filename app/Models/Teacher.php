<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model {

    use HasFactory;

    protected $fillable = [
        'teacherID', 'user_id', 'created_course', 'experienced_years', 'bio'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($teacher) {
            $latestTeacher = Teacher::latest('teacherID')->first();
            $teacherID = 'T' . str_pad((int) substr($latestTeacher->teacherID ?? 'T00000', 1) + 1, 5, '0', STR_PAD_LEFT);
            $teacher->teacherID = $teacherID;
        });
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courses() {
        return $this->hasMany(Course::class, 'teacherID', 'teacherID');
    }
}
