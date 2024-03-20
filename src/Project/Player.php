<?php

namespace App\Project;

class Player
{
    public string $name;
    public float $balance;
    public mixed $hands;
    public mixed $bets;


    public function __construct(string $name, int $numHands = 1, float $bankBalance = 0)
    {
        $this->name = $name;
        $this->hands = [];
        for ($i = 0; $i < $numHands; $i++) {
            $this->hands[] = new CardHand();
        }
        $this->balance = $bankBalance;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getCardHand(int $handNum): CardHand
    {
        return $this->hands[$handNum];
    }
    public function getCards(): mixed
    {
        $output = [];
        foreach ($this->hands as $cardHand) {
            $output[] = $cardHand->getCards();
        }
        return $output;
    }
    public function getMinSum(): mixed
    {
        $output = [];
        foreach ($this->hands as $cardHand) {
            $output[] = $cardHand->getMinSum();
        }
        return $output;
    }
    public function getMaxSum(): mixed
    {
        $output = [];
        foreach ($this->hands as $cardHand) {
            $output[] = $cardHand->getMaxSum();
        }
        return $output;
    }
    public function cleanHands(): void
    {
        foreach ($this->hands as $cardHand) {
            $cardHand->cleanHand();
        }
    }
    public function getNumOfHands(): int
    {
        return count($this->hands);
    }
    public function raiseBalance(float $amount): void
    {
        $this->balance += $amount;
    }
    public function decreaseBalance(float $amount): void
    {
        $this->balance -= $amount;
    }
    public function getBalance(): float
    {
        return $this->balance;
    }
    public function addBalance(int $amount): float
    {
        $this->balance += $amount;
        return $this->balance;
    }
    public function setBets(mixed $bets): void
    {
        $this->bets = $bets;
    }
    public function getBets(): mixed
    {
        return $this->bets;
    }
    public function doubleBets(int $handNum): void
    {
        $this->bets[$handNum] = $this->bets[$handNum] * 2;
    }

}
