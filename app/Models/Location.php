<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Location extends Model
{
    protected $fillable = ['name', 'state', 'country', 'slug'];

    protected static function booted()
    {
        static::creating(function ($location) {
            $location->slug = $location->slug ?: Str::slug($location->name);
        });
    }

    public function marketData()
    {
        return $this->hasMany(MarketData::class);
    }
}
