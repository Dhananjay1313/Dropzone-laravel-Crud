<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $table = 'dropzone';

    protected $fillable = [
        'firstname',
        'lastname',
        'gender',
        'status',
        'langauge'
    ];
}
