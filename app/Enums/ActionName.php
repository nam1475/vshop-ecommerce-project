<?php
namespace App\Enums;

enum ActionName: string {
    case Index = 'index';
    case Add = 'add';
    case Edit = 'edit';
    case Delete = 'delete';
}