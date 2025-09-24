<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public $table = 'reservation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'reservee',
        'date',
        'r_date',
        'time',
        'hours',
        'room_id',
        'address',
        'contactno',
    ];
}
