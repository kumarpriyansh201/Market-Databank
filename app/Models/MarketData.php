<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketData extends Model
{
    protected $table = 'market_data';

    protected $fillable = [
        'user_id', 'product_id', 'category_id', 'location_id',
        'price', 'min_price', 'max_price', 'demand_level', 'supply_level',
        'notes', 'source', 'data_date', 'status', 'rejection_reason',
        'approved_by', 'approved_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'data_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_datasets')
            ->withTimestamps();
    }

    public function interactions()
    {
        return $this->hasMany(DatasetInteraction::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
