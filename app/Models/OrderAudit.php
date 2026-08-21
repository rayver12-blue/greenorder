<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAudit extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'actor_id',
        'actor_role',
        'action',
        'message',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
