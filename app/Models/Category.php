<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = $category->slug ?: Str::slug($category->name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function marketData()
    {
        return $this->hasMany(MarketData::class);
    }
}
