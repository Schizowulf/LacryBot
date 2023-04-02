<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'symbol',
        'operation',
        'trigger_price',
        'quantity',
        'stop_loss',
        'take_profit',
        'order_status',
        'parent_order',
        'fix_type',
        'done_at_time',
        'done_at_price',
    ];
}
