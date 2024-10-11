<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table ='customers';

    protected $fillable = ['name', 'email', 'password', 'phone', 'gender', 'date_of_birth', 'email_verified_at',
                           'provider', 'provider_id', 'provider_token', 'remember_token'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function customerAddress()
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
