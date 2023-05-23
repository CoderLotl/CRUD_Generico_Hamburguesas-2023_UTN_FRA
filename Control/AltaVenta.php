<?php

if(isset($_POST['email']) && $_POST['email'] != null && $_POST['email'] != '' && isset($_POST['type']) && isset($_POST['name']) && isset($_POST['amount']))
{
    $sale = new Sale($_POST['email'], $_POST['name'], $_POST['type'], $_POST['amount'], $_POST['dressing']);

    if(isset($_POST['idVoucher']))
    {
        $idVoucher = $_POST['idVoucher'];
    }
    else
    {
        $idVoucher = 0;
    }

    if(ExecuteSale($sale, $idVoucher))
    {
        $email = explode('@', $_POST['email']);
        $destino = './ImagenesDeLaVenta/2023/'. $_POST['type'] . '-' . $_POST['name'] . '-' . $email[0] . '-' . date("d-m-Y-H-i-s") . '.jpg';
        move_uploaded_file($_FILES['file']['tmp_name'], $destino);
        return http_response_code(200);
    }
    else
    {
        die('error');
    }
}
else
{
    http_response_code(400);
}

function ExecuteSale($sale, int $idVoucher = 0)
{
    $dataAccess = new DataAccess();
    $burgers = $dataAccess->ReadObjectsFromFile('burgers');    
    $voucherUsed = UseVoucher($idVoucher);    

    foreach($burgers as $burger)
    {
        if($burger->type == $sale->type && $burger->name == $sale->name && $burger->amount >= $sale->amount)
        {
            if($voucherUsed == true)
            {
                $total = $sale->amount * $burger->price;
                $sale->total = $total - ($total * 10 / 100);
            }
            else
            {
                $sale->total = $sale->amount * $burger->price;
            }

            $burger->amount = $burger->amount - $sale->amount;
            $dataAccess->SaveToFile($burgers, 'burgers');
            $dataAccess->SaveObjectToFile($sale, 'sales');
            return true;
        }
    }
}

function UseVoucher($idVoucher)
{
    if($idVoucher > 0)
    {
        $dataAccess = new DataAccess();
        $vouchers = $dataAccess->ReadObjectsFromFile('vouchers');

        if($vouchers != false && $vouchers > 0)
        {
            $currentDate = new DateTime();            
            foreach($vouchers as $voucher)
            {
                $voucherDate = DateTime::createFromFormat("d/m/Y-H:i:s", $voucher->date);
                $interval = $voucherDate->diff($currentDate)->days;

                if($voucher->id == $idVoucher && $interval <= 3)
                {
                    $voucher->used = true;
                    $dataAccess->SaveToFile($vouchers, 'vouchers');
                    return true;
                }
            }
        }
    }
    else
    {
        return false;
    }
}

?>
?>