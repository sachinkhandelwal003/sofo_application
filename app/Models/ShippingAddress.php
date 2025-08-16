<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingAddress  extends Model
{
    use HasFactory, SoftDeletes;

  
    protected $fillable = ['user_id','storeiteams_id', 'type', 'phone', 'city','pincode', 'state'];

    public function storeItem()
    {
        return $this->belongsTo(Storeiteam::class, 'storeiteams_id'); // or correct foreign key
    }
}
