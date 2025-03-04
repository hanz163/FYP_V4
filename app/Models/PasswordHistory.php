<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordHistory extends Model {
    use HasFactory;

    // Define the fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'password',
    ];
}