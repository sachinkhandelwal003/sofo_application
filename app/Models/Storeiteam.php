<?php

namespace App\Models;

use App\Traits\CustomScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storeiteam extends Model
{
    use HasFactory, SoftDeletes, CustomScopes;

    protected $fillable = [
        'app_user_id',
        'name',
        'image',
        'price',
        'about',
        'brand',
        'size',
        'status'
    ];
}
