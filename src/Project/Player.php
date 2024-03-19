<?php

namespace App\Project;

class Player
{
    public string $name;
    public int $balance;
    public array $hands;
    public array $bets;


    public function __construct(string $name, int $numHands = 1, $bank_balance = 0)
    {
        $this->name = $name;
        $this->hands = [];
        for ($i = 0; $i < $numHands; $i++) {
            $this->hands[] = new CardHand();
        }
        $this->balance = $bank_balance;
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
        foreach ($this->hands as $cardHand) {
            $output[] = $cardHand->getCards();
        }
        return $output;
    }
    public function getMinSum(): mixed
    {
        foreach ($this->hands as $cardHand) {
            $output[] = $cardHand->getMinSum();
        }
        return $output;
    }
    public function getMaxSum(): mixed
    {
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
    public function raiseBalance(int $amount): void
    {
        $this->balance += $amount;
    }
    public function decreaseBalance(int $amount): void
    {
        $this->balance -= $amount;
    }
    public function getBalance(): int
    {
        return $this->balance;
    }
    public function addBalance(int $amount): int
    {
        $this->balance += $amount;
        return $this->balance;
    }
    public function setBets(array $bets): void
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
