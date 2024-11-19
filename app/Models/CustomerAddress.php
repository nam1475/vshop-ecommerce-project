<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'province',
        'district',
        'ward',
        'phone',
        'is_main',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);   
    }
}
