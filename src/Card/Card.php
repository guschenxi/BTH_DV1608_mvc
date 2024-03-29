<?php

namespace App\Card;

class Card
{
    public string $color;
    public string $number;

    public function __construct(string $color, string $number)
    {
        $this->color = $color;
        $this->number = $number;
    }

    public function getValue(): mixed
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
