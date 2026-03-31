<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedDataset extends Model
{
    protected $fillable = [
        'user_id',
        'market_data_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marketData()
    {
        return $this->belongsTo(MarketData::class);
    }
}
