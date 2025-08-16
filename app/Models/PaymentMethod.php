<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id','storeiteams_id', 'method', 'card_holder_name',
        'card_number', 'card_last_digits', 'expiry_date', 'paypal_email'
    ];

  public function storeItem()
    {
        return $this->belongsTo(Storeiteam::class, 'storeiteams_id');
    }
}

