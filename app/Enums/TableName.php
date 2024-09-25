<?php
namespace App\Enums;

enum TableName: string {
    case Product = 'product';
    case Category = 'category';
    case Brand = 'brand';
    case Order = 'order';
    case Customer = 'customer';
    case Role = 'role';
    case Permission = 'permission';
}

