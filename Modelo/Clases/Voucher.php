<?php

class Voucher
{
    public $id;
    public $used;
    public $date;

    public function __construct()    
    {
        $this->used = false;
        $this->date = date("d/m/Y-H:i:s");
    }
}

?>