<?php
namespace App\Traits;

trait InStock
{
    public static function creatingAndUpdatingInStock()
    {
        /* Kế thừa phương thức boot() từ class cha để đảm bảo rằng:
        - Tất cả các sự kiện của model (như creating, updating, ...) đều được thiết lập chính xác.
        - Sau đó, chúng ta có thể thêm logic riêng như việc tự động đặt in_stock cho Product. 
        */
        // parent::boot();
        
        /* - static::creating(): Từ khóa static được sử dụng để đăng ký các sự kiện model, chẳng hạn như 
        creating, updating
        - Event này sẽ được kích hoạt ngay trước khi model được tạo (insert vào database).
        */
        static::creating(function ($product) {
            $product->in_stock = $product->quantity > 0 ? 1 : 0;
        });

        /* - static::updating(): 
        - Event này sẽ được kích hoạt ngay trước khi model được thay đổi (update trong database).
        */
        static::updating(function ($product) {
            $product->in_stock = $product->quantity > 0 ? 1 : 0;
        });
    }
}