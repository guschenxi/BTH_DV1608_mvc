<?php

namespace App\Project;

class Player
{
    public string $name;
    public int $score;
    public array $hands;


    public function __construct(string $name, int $numHands = 1)
    {
        $this->name = $name;
        $this->score = 0;
        $this->hands = [];
        for ($i = 0; $i < $numHands; $i++) {
            $this->hands[] = new CardHand();
        }
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
    public function addCardToHand(Card $card, int $handIndex = 0): void
    {
        if ($handIndex >= 0 && $handIndex < count($this->hands)) {
            $this->hands[$handIndex]->addCard($card);
        }
    }
}
