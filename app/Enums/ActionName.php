<?php
namespace App\Enums;

enum ActionName: string {
    case List = 'list';
    case Add = 'add';
    case Edit = 'edit';
    case Delete = 'delete';
}