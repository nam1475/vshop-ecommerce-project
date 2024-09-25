<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedAndUpdatedBy;

class Order extends Model
{
    use HasFactory, CreatedAndUpdatedBy;
    
    protected $table = 'orders';
    protected $fillable = ['total_price', 'status', 'session_id', 'customer_address_id'];

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function customerAddress() {
        return $this->belongsTo(CustomerAddress::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }

    
    
}
