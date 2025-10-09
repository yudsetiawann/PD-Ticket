<?php

namespace App\Models;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'order_code',
        'quantity',
        'total_price',
        'status',
        'midtrans_order_id',
        'midtrans_token',
        'customer_name',
        'phone_number',
        'school',
        'level',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
