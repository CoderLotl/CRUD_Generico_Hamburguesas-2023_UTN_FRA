<?php

class Burger
{
    public $id;
    public $amount;
    public $name;
    public $price;
    public $type;
    public $dressing;

    public function __construct(string $name, int $amount, float $price, string $type, string $dressing)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->price = $price;
        $this->type = $type;
        $this->dressing = $dressing;
    }

    public function __toString()
    {
        return "Name: {$this->name} | Amount: {$this->amount} | Price: $ {$this->price} | Type: {$this->type} | Dressing: {$this->dressing}";
    }
}

?>