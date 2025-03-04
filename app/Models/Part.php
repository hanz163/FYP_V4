<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model {

    use HasFactory;

    protected $primaryKey = 'partID';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'partID',
        'chapterID',
        'title',
        'description',
        'order',
    ];

    public function chapter() {
        return $this->belongsTo(Chapter::class, 'chapterID', 'chapterID');
    }

    public function difficulties() {
        return $this->hasMany(Difficulty::class, 'partID');
    }

    public function questions() {
        return $this->hasMany(Question::class, 'partID', 'partID');
    }

    public static function generatePartID() {
        $latestPart = self::orderBy('partID', 'desc')->first();
        $num = $latestPart ? (int) substr($latestPart->partID, 1) + 1 : 1;
        return 'P' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    public function lectureNotes() {
        return $this->hasMany(LectureNote::class, 'partID', 'partID');
    }

    public function lectureVideos() {
        return $this->hasMany(LectureVideo::class, 'partID', 'partID');
    }
}
