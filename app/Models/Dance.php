<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dance extends Model
{
    public $table = 'dance';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
    ];

}
