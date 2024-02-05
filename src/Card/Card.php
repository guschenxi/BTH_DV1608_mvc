<?php

namespace App\Card;

class Card
{
    public $color;
    public $number;

    public function __construct($color, $number)
    {
        $this->color = $color;
        $this->number = $number;
    }

    public function getValue(): array
    {
        return [$this->color, $this->number];
    }
    public function getColor(): string
    {
        return $this->color;
    }
    public function getNumber(): string
    {
        return $this->number;
    }
    public function getAsString(): string
    {
        return "{$this->color}-{$this->number}";
    }
}
