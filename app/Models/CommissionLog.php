<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'affiliate_id',
        'commission_amount',
       
    ];

    //  relationships 
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}
