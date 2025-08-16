<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
   use HasFactory, SoftDeletes;

    protected $table = 'user_addresses';

   protected $fillable = [
        'user_id', 'full_name', 'phone', 'pincode', 'house_no', 'road_name', 'city', 'state', 'type'
    ];
}


