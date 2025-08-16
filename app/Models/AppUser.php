<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // If using Sanctum
// use Laravel\Passport\HasApiTokens; // If using Passport

class AppUser extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, HasApiTokens;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'app_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'become_vendor',
        'profile_image',
        'password',
        'verify_otp',
        'otp_expiry',
        'is_verified',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'verify_otp',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expiry' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Automatically hash the password when setting it.
     *
     * @param  string  $value
     * @return void
     */
 
    /**
     * Check if OTP is expired.
     *
     * @return bool
     */
    public function isOtpExpired()
    {
        return now()->greaterThan($this->otp_expiry);
    }

    public function vendor()
{
    return $this->hasOne(Vendor::class, 'app_user_id'); // use correct foreign key
}

}