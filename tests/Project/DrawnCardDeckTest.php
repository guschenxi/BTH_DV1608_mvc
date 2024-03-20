<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class DrawnCardDeckTest extends TestCase
{
    public function testDrawnCardDeck(): void
    {
        $drawnCardDeck = new DrawnCardDeck();
        $this->assertInstanceOf("\App\Project\DrawnCardDeck", $drawnCardDeck);
        $drawnCardDeck->addCard(new Card("heart", "queen"));
        $drawnCardDeck->addCard(new Card("spade", "7"));
        $drawnCardDeck->addCard(new Card("heart", "6"));
        $drawnCardDeck->addCard(new Card("spade", "5"));

        $this->assertIsArray($drawnCardDeck->getAllDrawnCardsStat());
        $this->assertIsArray($drawnCardDeck->getSuitedDrawnCardsStat());
        $this->assertArrayHasKey("heart", $drawnCardDeck->getSuitedDrawnCardsStat());
        $this->assertIsArray($drawnCardDeck->getRankedDrawnCardsStat());
        $this->assertArrayHasKey("queen", $drawnCardDeck->getRankedDrawnCardsStat());
    }
}
