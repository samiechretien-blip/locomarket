<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'price', 'stock', 'image_url'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected $appends = ['image_full_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageFullUrlAttribute(): ?string
    {
        if (! $this->image_url) {
            return null;
        }
        if (str_starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        return Storage::disk('public')->url($this->image_url);
    }
    public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
}