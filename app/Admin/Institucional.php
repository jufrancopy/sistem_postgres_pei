<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Institucional extends Model
{
    protected $table = 'institucionales';
    
    protected $fillable = [
        'mision',
        'vision'
    ];
}
