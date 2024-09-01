<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

trait CreatedAndUpdatedBy
{
    public static function bootCreatedAndUpdatedBy()
    {
        static::creating(function ($model) {
            if (Auth::guard('customer')->check()) {
                $model->created_by = auth('customer')->user()->id;
                $model->updated_by = auth('customer')->user()->id;
            }
        });

        static::updating(function ($model) {
            if (Auth::guard('customer')->check()) {
                $model->updated_by = auth('customer')->user()->id;
            }
        });
    }

    public function customerCreated(){
        return $this->hasOne(Customer::class, 'id', 'created_by');
    }

    public function customerUpdated(){
        return $this->hasOne(Customer::class, 'id', 'updated_by');
    }
}