<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Admin extends Authenticatable
{

     /** @use HasFactory<\Database\Factories\UserFactory> */
     use HasFactory, Notifiable;

    public $table = 'admin';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'user_id',
        'role_id',
        'password',
    ];

    public function username()
    {
        return 'user_id';
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected $hidden = [
        'password',
        'remember_token', // optional but recommended
    ];
}
