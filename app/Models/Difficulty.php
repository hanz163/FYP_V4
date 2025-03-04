<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Difficulty extends Model {

    use HasFactory;

    protected $primaryKey = 'DifficultyID';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['DifficultyID', 'partID', 'level'];

    public function part() {
        return $this->belongsTo(Part::class, 'partID');
    }

    public function questions() {
        return $this->hasMany(Question::class, 'DifficultyID');
    }

    protected static function boot() {
        parent::boot();
        static::creating(function ($difficulty) {
            $lastID = self::orderBy('DifficultyID', 'desc')->first();
            $nextID = $lastID ? 'D' . str_pad(substr($lastID->DifficultyID, 1) + 1, 5, '0', STR_PAD_LEFT) : 'D00001';
            $difficulty->DifficultyID = $nextID;
        });
    }
}
