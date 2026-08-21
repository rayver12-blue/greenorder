<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'order_type',
        'notes',
        'payment_method',
        'payment_status',
        'payment_reference',
        'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function audits()
    {
        return $this->hasMany(OrderAudit::class)->latest();
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => '#f59e0b',
            'processing' => '#3b82f6',
            'delivered'  => '#16a34a',
            'cancelled'  => '#ef4444',
            default      => '#6b7280',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Order Placed',
            'processing' => 'Being Prepared',
            'delivered'  => 'Completed',
            'cancelled'  => 'Cancelled',
            default      => ucfirst($this->status),
        };
    }
}
