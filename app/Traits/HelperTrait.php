<?php

namespace App\Traits;

use App\Http\Resources\OrderResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HelperTrait
{
    public function getChildrenByParent($categoryParent)
    {
        return $categoryParent->children()->get();
    }

    public function getOrder($orderId)
    {
        // $order = Order::with(['orderItems.product.images', 'customerAddress.customer'])->where('id', $orderId)->first();
        $order = Order::with(['orderItems.product', 'customerAddress.customer'])->where('id', $orderId)->first();
        return new OrderResource($order);
    }

    public function uniqueImageName($image, $path)
    {
        $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $publicPath = public_path($path);
        $image->move($publicPath, $uniqueName); 
        return $uniqueName;
    }

    public function deleteRows($request, $model){
        $data = $model::whereIn('id', $request->input('ids'))->get();
        foreach($data as $item){
            $item->delete();
        }
    }
}



