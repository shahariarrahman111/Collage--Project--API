<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SslCommerceAccount extends Model
{
   
    use HasFactory;
    
     
    protected $fillable = [
        'store_id',
        'store_passwd',
        'init_url',
        'success_url',
        'fail_url',
        'cancel_url',
        'ipn_url',
        'currency'
       
    ];





}
