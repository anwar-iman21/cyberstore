<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'main_image',
        'sku',
        'status',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'status' => 'boolean',
            'featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getMainImageUrlAttribute(): ?string
    {
        return $this->main_image ? asset('storage/' . $this->main_image) : null;
    }
}
