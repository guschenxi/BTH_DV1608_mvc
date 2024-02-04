<?php

namespace App\Card;

class CardHand
{
    private $cards = [];

    public function addCard(Card $card)
    {
        $this->cards[] = $card;
    }

    public function getCards()
    {
        return $this->cards;
    }
    public function getAmount()
    {
        return count($this->cards);
    }
}
