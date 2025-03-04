<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model {

    use HasFactory;

    protected $table = 'chapters';
    protected $primaryKey = 'chapterID';
    public $incrementing = false; // Since chapterID is a string (e.g., CH0001)
    protected $keyType = 'string';
    protected $fillable = [
        'chapterID',
        'chapterName',
        'description',
        'position',
        'image',
        'courseID'
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($chapter) {
            // Get the latest chapterID
            $latestChapter = Chapter::latest('chapterID')->first();

            if ($latestChapter) {
                // Extract numeric part and increment it
                $latestNumber = intval(substr($latestChapter->chapterID, 2));
                $newNumber = str_pad($latestNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                // If no chapters exist, start from 0001
                $newNumber = '0001';
            }

            // Assign the new chapterID
            $chapter->chapterID = 'CH' . $newNumber;
        });
    }

    public function course() {
        return $this->belongsTo(Course::class, 'courseID', 'courseID');
    }

    public function parts() {
        return $this->hasMany(Part::class, 'chapterID', 'chapterID');
    }
}
