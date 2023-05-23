<?php

class Sale
{    
    public $id;
    public $email;
    public $name;
    public $type;
    public $amount;    
    public $date;
    public $dressing;
    public $total;

    public function __construct(string $email, string $name, string $type, int $amount, string $dressing)
    {
        $this->email = $email;
        $this->name = $name;
        $this->dressing = $dressing;
        $this->type = $type;
        $this->amount = $amount;
        $this->date = date("d/m/Y-H:i:s");
        $this->total = 0;
    }
}

?>