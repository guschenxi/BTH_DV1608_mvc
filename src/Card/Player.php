<?php

namespace App\Card;

class Player extends CardHand
{
    public $name;
    //public $score;
    
    public function __construct($name)
    {
        $this->name = $name;
        $this->score = 0;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function raiseScore(): void
    {
        $this->score += 1;
    }
    public function getScore(): int
    {
        return $this->score;
    }
}
