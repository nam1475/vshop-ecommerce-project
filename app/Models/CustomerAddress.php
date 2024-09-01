<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'address1',
        'address2',
        'city',
        'state',
        'phone',
        'zip_code',
        'is_main',
        'country_code',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);   
    }
}