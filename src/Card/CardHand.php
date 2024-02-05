<?php

namespace App\Card;

class CardHand
{
    protected $cards = [];

    public function addCard(Card $card)
    {
        $this->cards[] = $card;
    }

    public function getCards()
    {
        return $this->cards;
    }
    public function cleanHand(): void
    {
        $this->cards = [];
    }
    public function getAmount()
    {
        return count($this->cards);
    }
    public function getMinSum(): int
    {
        $sum = 0;
        foreach ($this->cards as $card) {
            $number = self::changeNumberMin($card->getNumber());
            $sum += $number;
        }
        return $sum;
    }
    public function getMaxSum(): int
    {
        $sum = 0;
        foreach ($this->cards as $card) {
            $number = self::changeNumberMax($card->getNumber());
            $sum += $number;
        }
        return $sum;
    }
    public function changeNumberMin($number): int
    {
        switch ($number) {
            case "king": $number = 13;
                break;
            case "queen": $number = 12;
                break;
            case "jack" : $number = 11;
                break;
            case "ace" : $number = 1;
                break;
            default : $number = $number;
        }
        return $number;
    }
    public function changeNumberMax($number): int
    {
        switch ($number) {
            case "king": $number = 13;
                break;
            case "queen": $number = 12;
                break;
            case "jack" : $number = 11;
                break;
            case "ace" : $number = 14;
                break;
            default : $number = $number;
        }
        return $number;
    }
}
