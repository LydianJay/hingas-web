<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    public $table = 'payments';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'admin_id',
        'amount',
        'enrollment_id',
        'date',
    ];
}
