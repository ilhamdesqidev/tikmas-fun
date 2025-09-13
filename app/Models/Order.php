<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'promo_id',
        'customer_name',
        'whatsapp_number',
        'branch',
        'visit_date',
        'ticket_quantity',
        'total_price',
        'status',
        'snap_token',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }
}