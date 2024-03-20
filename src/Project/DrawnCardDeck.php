<?php

namespace App\Project;

use App\Project\Card;
use App\Project\CardDeckNoJoker;

class DrawnCardDeck extends CardDeckNoJoker
{
    public mixed $cards;
    public mixed $rankedCards = [];
    public mixed $suitedCards = [];
    public mixed $allCards = [];

    public function __construct()
    {
        $this->cards = [];
    }
    public function addCard(Card $card): void
    {
        $this->cards[] = $card;

        $rankedKey = $card->getNumber();

        if (!isset($this->rankedCards[$rankedKey])) {
            $this->rankedCards[$rankedKey] = 1;
        } elseif (isset($this->rankedCards[$rankedKey])) {
            $this->rankedCards[$rankedKey]++;
        }

        $suitedKey = $card->getColor();

        if (!isset($this->suitedCards[$suitedKey])) {
            $this->suitedCards[$suitedKey] = 1;
        } elseif (isset($this->suitedCards[$suitedKey])) {
            $this->suitedCards[$suitedKey]++;
        }

        $allKey = $card->getNumber() . ' of ' . $card->getColor();

        if (!isset($this->allCards[$allKey])) {
            $this->allCards[$allKey] = 1;
        } elseif (isset($this->allCards[$allKey])) {
            $this->allCards[$allKey]++;
        }
    }
    public function getAllDrawnCardsStat(): mixed
    {
        return $this->allCards;
    }
    public function getRankedDrawnCardsStat(): mixed
    {
        return $this->rankedCards;
    }
    public function getSuitedDrawnCardsStat(): mixed
    {
        return $this->suitedCards;
    }
}
