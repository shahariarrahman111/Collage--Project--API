<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
   
     use HasFactory;


     protected $fillable = [
        'user_id',
        'tran_id',
        'val_id',
        'cus_details',
        'delivery_status',
        'ship_details',
        'amount',
        'currency',
        'payment_status',
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
