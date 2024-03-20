<?php

namespace App\Project;

use App\Project\Card;
use App\Project\CardDeckNoJoker;

class CardDeck extends CardDeckNoJoker
{
    public mixed $cards = [];

    public function __construct(int $num)
    {
        $colors = ['heart', 'diamond', 'club', 'spade'];
        $numbers = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];
        for ($i = 0; $i < $num; $i++) {
		    foreach ($colors as $color) {
		        foreach ($numbers as $number) {
		            $this->cards[] = new Card($color, $number);
		        }
		    }
    	}
    }
}
