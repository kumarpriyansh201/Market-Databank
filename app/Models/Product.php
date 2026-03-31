<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'category_id', 'unit', 'image', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->slug = $product->slug ?: Str::slug($product->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function marketData()
    {
        return $this->hasMany(MarketData::class);
    }
}
