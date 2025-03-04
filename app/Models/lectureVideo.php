<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureVideo extends Model {

    use HasFactory;

    protected $fillable = [
        'partID',
        'title',
        'file_path',
    ];

    public function part() {
        return $this->belongsTo(Part::class, 'partID', 'partID');
    }
}
