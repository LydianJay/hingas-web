<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleSubm extends Model
{
    public $table = 'role_subm';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'role_id',
        'subm_id',
    ];
}
