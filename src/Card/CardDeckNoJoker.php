<?php

namespace App\Card;

use App\Card\Card;

class CardDeckNoJoker
{
    public mixed $cards = [];

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
    /**
     * Shuffle cards
     */
    public function shuffleCards(): void
    {
        shuffle($this->cards);
    }
    /**
     * draw card
     */
    public function drawCard(): ?Card
    {
        if (empty($this->cards)) {
            //throw new \RuntimeException('The deck is empty.');
            return null;
        }
        $randomIndex = array_rand($this->cards);
        $drawnCard = $this->cards[$randomIndex];
        $this->removeCardFromDeck($randomIndex);
        return $drawnCard;
    }
    /**
     * Remove a card from the deck by given index
     * @param mixed $drawnCardIndex the given index
     *
     */
    public function removeCardFromDeck(mixed $drawnCardIndex): void
    {
        $index = $drawnCardIndex;

        array_splice($this->cards, $index, 1);
    }

    /**
     * Return the deck of cards
     * @return mixed array the deck of cards
     *
     */
    public function getCards(): mixed
    {
        return $this->cards;
    }
    /**
     * Return the count of the current deck
     * @return int the count
     *
     */
    public function getAmount(): int
    {
        return count($this->cards);
    }
    /**
     * check if there are enough cards left on the deck
     * @return bool true or false
     *
     */
    public function hasEnoughCards(): bool
    {
        $minimumCardsForRound = 6;

        return self::getAmount() >= $minimumCardsForRound;
    }
}
