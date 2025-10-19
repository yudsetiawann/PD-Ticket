<?php

namespace App\Models;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia; // <-- Add this line
use Spatie\MediaLibrary\InteractsWithMedia; // <-- Add this line

class Order extends Model implements HasMedia
{
    // Add 'use InteractsWithMedia;' here
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'event_id',
        'user_id',
        'order_code',
        'ticket_code',
        'quantity',
        'total_price',
        'status',
        'midtrans_order_id',
        'midtrans_token',
        'customer_name',
        'phone_number',
        'school',
        'level',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
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
