<?php

namespace App\Project;

class CardHand
{
    public mixed $cards = [];

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function getCards(): mixed
    {
        return $this->cards;
    }
    public function cleanHand(): void
    {
        $this->cards = [];
    }
    public function getAmount(): int
    {
        return count($this->cards);
    }
    public function getMinSum(): int
    {
        $sum = 0;
        foreach ($this->cards as $card) {
            $number = $this->changeNumberMin($card->getNumber());
            $sum += $number;
        }
        return $sum;
    }
    public function getMaxSum(): int
    {
        $sum = 0;
        foreach ($this->cards as $card) {
            $number = $this->changeNumberMax($card->getNumber());
            $sum += $number;
        }
        return $sum;
    }
    public function changeNumberMin(mixed $number): int
    {
        switch ($number) {
            case "king":
                $number = 10;
                break;
            case "queen":
                $number = 10;
                break;
            case "jack":
                $number = 10;
                break;
            case "ace":
                $number = 1;
                break;
            default:
                $number = $number;
        }
        return $number;
    }
    public function changeNumberMax(mixed $number): int
    {
        switch ($number) {
            case "king":
                $number = 10;
                break;
            case "queen":
                $number = 10;
                break;
            case "jack":
                $number = 10;
                break;
            case "ace":
                $number = 11;
                break;
            default:
                $number = $number;
        }
        return $number;
    }
}
