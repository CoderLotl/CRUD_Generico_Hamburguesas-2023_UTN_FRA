<?php

if(isset($_POST['name']) && $_POST['name'] != '' && isset($_POST['type']) && $_POST['type'] != '')
{
    $found = false;
    $dataAccess = new DataAccess();
    $burgers = $dataAccess->ReadObjectsFromFile('burgers');

    foreach($burgers as $burger)
    {
        if($burger->type == $_POST['type'] && $burger->name == $_POST['name'] && $burger->amount > 0)
        {
            $found = true;
            break;            
        }        
    }
    
    if($found == true)
    {
        echo 'Si hay';
        return true;
    }
    else
    {
        echo 'No hay';
        return false;
    }
}
else
{    
    die(http_response_code(400));
}

?>