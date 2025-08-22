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
        'session_count',
        'price',
        'is_active',
    ];



    public static function getPrice($id) {
        $dance = self::find($id);
        return $dance ? 0 : $dance->price;
    }
}
