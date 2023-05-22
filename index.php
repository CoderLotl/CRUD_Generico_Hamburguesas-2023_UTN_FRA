<?php
include './Modelo/Servicios/DataAccess.php';
include './Modelo/Clases/Burger.php';
include './Modelo/Clases/Voucher.php';
include './Modelo/Clases/Sale.php';

DataAccess::SetValues(['sales' => './sales.json', 'burgers' => './burgers.json', 'vouchers' => './vouchers.json']);

switch($_SERVER['REQUEST_METHOD'])
{
    case 'GET':
        break;
    case 'POST':
        break;
    case 'DELETE':
        break;
    case 'PUT':
        break;
    default:        
        die(http_response_code(400));
}

