<?php

if(isset($_POST['amount']) && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['type']) && isset($_POST['dressing']))
{
    $burger = new Burger($_POST['name'], $_POST['amount'], $_POST['price'], $_POST['type'], $_POST['dressing']);
    $dataAccess = new DataAccess();
    if($dataAccess->SaveObjectToFile($burger, 'burger', true))
    {        
        $destino = './ImagenesDeHamburguesas/2023/'. $_POST['type'] . '-' . $_POST['name'] . '.jpg';
        move_uploaded_file($_FILES['file']['tmp_name'], $destino);
        http_response_code(200);
    }
}
else
{    
    die(http_response_code(400));
}

?>