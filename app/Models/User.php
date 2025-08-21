<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
class User extends Model
{
   
    public $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'rfid',
        'email',
        'password',
        'email_verified_at',
        'img',
        'fname',
        'mname',
        'lname',
        'gender',
        'dob',
        'contactno',
        'address',
        'is_active',
        'e_contact',
        'e_contact_no',
        'photo',
        'is_admin',
    ];



}
