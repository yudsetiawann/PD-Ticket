<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'location',
        'ticket_price',
        'ticket_quota',
        'starts_at',
        'ends_at',
        'thumbnail',
        'user_id',

        // --- TAMBAHKAN SEMUA KOLOM BARU DI SINI ---
        'description',
        'location_map_link',
        'contact_person_name',
        'contact_person_phone',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnails')->singleFile();
        // Add this new collection for the gallery
        $this->addMediaCollection('gallery');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug) && ! empty($model->title)) {
                $model->slug = Str::slug($model->title);
            }
        });

        // setelah create, kalau ada path file di atribut thumbnail, pindahkan ke media collection
        static::created(function ($model) {
            if (! empty($model->thumbnail) && is_string($model->thumbnail)) {
                $path = storage_path('app/public/' . ltrim($model->thumbnail, '/'));
                if (file_exists($path)) {
                    $model->addMedia($path)
                        ->preservingOriginal()
                        ->toMediaCollection('thumbnails');
                    // opsional: hapus file 'raw' jika tidak diperlukan lagi:
                    // @unlink($path);
                }
            }
        });

        static::saving(function ($event) {
            if ($event->ticket_sold > $event->ticket_quota) {
                throw new \Exception('Jumlah tiket terjual tidak boleh melebihi kuota.');
            }
        });
    }
}
