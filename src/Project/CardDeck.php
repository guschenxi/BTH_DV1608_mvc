<?php

namespace App\Project;

use App\Project\Card;

class CardDeck extends CardDeckNoJoker
{
    public mixed $cards = [];

    public function __construct()
    {
        parent::__construct();
        $this->cards[] = new Card('red', 'joker');
        $this->cards[] = new Card('black', 'joker');
    }
}
