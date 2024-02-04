<?php

namespace App\Card;

use App\Card\Card;

class CardDeckNoJoker
{
    protected $cards = [];

    public function __construct()
    {
        $colors = ['heart', 'diamond', 'club', 'spade'];
        $numbers = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];

        foreach ($colors as $color) {
            foreach ($numbers as $number) {
                $this->cards[] = new Card($color, $number);
            }
        }
    }
    /* Shuffle cards */
    public function shuffleCards(): void
    {
        shuffle($this->cards);
    }
    /* draw card */
    public function drawCard(): object
    {
        $randomIndex = array_rand($this->cards);
        $drawnCard = $this->cards[$randomIndex];
        $this->removeCardFromDeck($randomIndex);
        return $drawnCard;
    }
    /*
     * Remove a card from the deck by given index
     * @param int $drawnCardIndex the given index
     *
    */
    public function removeCardFromDeck(int $drawnCardIndex)
    {
        $index = $drawnCardIndex;

        array_splice($this->cards, $index, 1);
    }

    /*
     * Return the deck of cards
     * @return array the deck of cards
     *
    */
    public function getCards(): array
    {
        return $this->cards;
    }
    /*
     * Return the count of the current deck
     * @return int the count
     *
    */
    public function getAmount(): int
    {
        return count($this->cards);
    }
}
