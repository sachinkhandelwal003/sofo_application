<?php

namespace App\Models;

use App\Models\AppUser;
use App\Traits\CustomScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes, CustomScopes;

    protected $fillable = [
        'shop_name',
        'gst_no',
        'pan_no',
        'tanno',
        'address',
        'categories',
        'other_categories',
        'status',
        'app_user_id',
        'shop_image',
        'shop_time',
        'rating',
        'email',
        'contact',
        'website',
        'about',
        'delivery',
    ];


    /**
     * Boot method to handle model events.
     */
    protected static function booted()
    {
        static::created(function ($vendor) {
            $user = AppUser::find($vendor->app_user_id);
            if ($user) {
                $user->update(['become_vendor' => 1]);
            }
        });

        static::updated(function ($vendor) {
            $user = AppUser::find($vendor->app_user_id);
            if ($user) {
                if ($vendor->status == 1) {
                    $user->update(['become_vendor' => 2]);
                } elseif ($vendor->status == 0) {
                    $user->update(['become_vendor' => 1]);
                }
            }
        });
    }

    /**
     * Scope for approved vendors (status = 1).
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Relationship: Vendor belongs to AppUser.
     */
    public function user()
    {
        return $this->belongsTo(AppUser::class, 'app_user_id');
    }

    public function getCategoryNamesAttribute(): ?string
    {
        $categoryIds = [];

        // Check for array, JSON, or CSV string
        if (is_array($this->categories)) {
            $categoryIds = $this->categories;
        } elseif (is_string($this->categories)) {
            $json = json_decode($this->categories, true);
            if (is_array($json)) {
                $categoryIds = $json;
            } else {
                $categoryIds = explode(',', $this->categories);
            }
        }

        // Get category names
        $names = Category::whereIn('id', $categoryIds)->pluck('name')->toArray();

        // Return as comma-separated string
        return implode(', ', $names);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_vendor');
    }

}
