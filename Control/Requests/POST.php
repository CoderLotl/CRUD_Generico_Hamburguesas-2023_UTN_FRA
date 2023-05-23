<?php
if(isset($_POST['request']) && $_POST['request'] != null)
{
    switch($_POST['request'])
    {
        case 'altaHamburguesa':
            include './Control/HamburguesaCarga.php';
            break;
        default:
            die(http_response_code(400));
    }
}
else
{
    die(http_response_code(400));
}

?>