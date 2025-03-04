<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

    protected $primaryKey = 'courseID';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'courseName',
        'description',
        'category',
        'studentCount',
        'capacityOffered',
        'teacherID',
        'image',
        'display_order',
    ];

    // Boot method for custom ID generation
    protected static function boot() {
        parent::boot();

        static::creating(function ($course) {
            $latestCourse = Course::latest('courseID')->first();
            if ($latestCourse) {
                $latestID = (int) substr($latestCourse->courseID, 1);
                $course->courseID = 'C' . str_pad($latestID + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $course->courseID = 'C00001';
            }

            // Assign order if not set
            if (!$course->order) {
                $maxOrder = Course::max('order');
                $course->order = $maxOrder ? $maxOrder + 1 : 1;
            }
        });
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class, 'teacherID', 'teacherID');
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'student_course', 'courseID', 'studentID')
                        ->withPivot('progress', 'is_completed')
                        ->withTimestamps();
    }

    public function chapters() {
        return $this->hasMany(Chapter::class, 'courseID', 'courseID');
    }
}
