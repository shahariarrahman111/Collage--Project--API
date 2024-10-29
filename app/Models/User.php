<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'firstName',
        'lastName',
        'userName',
        'email',
        'password',
        'phone',
        'role'
    ] ;


    protected $attributes = ['otp'=> 0] ;





    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }



}
