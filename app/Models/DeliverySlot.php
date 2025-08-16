<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliverySlot  extends Model
{
    use HasFactory, SoftDeletes;

  
    protected $fillable = [
        'user_id','storeiteams_id','payment_methods_id','shipping_addresses_id','type', 'delivery_date', 'delivery_time'
    ];

    public function storeItem()
    {
        return $this->belongsTo(Storeiteam::class, 'storeiteams_id');
    }

     public function  paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_methods_id');
    }

     public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_addresses_id');
    }
}
