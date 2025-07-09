<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanceSession extends Model
{
    public $table = 'dance_session';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'enrollment_id',
        'time_in',
        'time_out',
        'date',
    ];
}
