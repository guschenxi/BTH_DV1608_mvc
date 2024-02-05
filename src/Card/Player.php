<?php

namespace App\Card;

class Player extends CardHand
{
    public string $name;
    public int $score;

    public function __construct(string $name)
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
