<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'market_data_id', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marketData()
    {
        return $this->belongsTo(MarketData::class);
    }
}
