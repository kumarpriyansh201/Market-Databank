<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatasetInteraction extends Model
{
    protected $fillable = [
        'market_data_id',
        'user_id',
        'interaction_type',
    ];

    public function marketData()
    {
        return $this->belongsTo(MarketData::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
