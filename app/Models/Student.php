<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model {

    use HasFactory;

    protected $fillable = [
        'studentID', 'user_id', 'progress_percentage', 'achievement'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($student) {
            $latestStudent = Student::latest('studentID')->first();
            $studentID = 'S' . str_pad((int) substr($latestStudent->studentID ?? 'S00000', 1) + 1, 5, '0', STR_PAD_LEFT);
            $student->studentID = $studentID;
        });
    }

    public function user() {
        return $this->belongsTo(User::class, 'userID');
    }

    public function courses() {
        return $this->belongsToMany(Course::class, 'student_course', 'studentID', 'courseID')
                        ->withPivot('progress', 'is_completed')
                        ->withTimestamps();
    }
}
