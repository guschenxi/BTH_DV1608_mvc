<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardDeck.
 */
class ProjCardDeckTest extends TestCase
{
    public function testCreateCardDeck(): void
    {
        $cardDeck = new CardDeck(1);
        $this->assertInstanceOf("\App\Project\CardDeck", $cardDeck);
        $this->assertEquals(52, $cardDeck->getAmount());

        // same test for CardDeckNoJoker
        $cardDeck->shuffleCards();
        $this->assertInstanceOf("\App\Project\CardDeckNoJoker", $cardDeck);

        $this->assertIsArray($cardDeck->getCards());
        $this->assertEquals(true, $cardDeck->hasEnoughCards());

        $card = $cardDeck->drawCard();
        $this->assertInstanceOf("\App\Project\Card", $card);

        $cardDeck->removeCardFromDeck(0);
        $this->assertEquals(50, $cardDeck->getAmount());
        for ($i = 0; $i < 50; $i++) {
            $cardDeck->removeCardFromDeck(0);
        }
        $this->assertEquals(0, $cardDeck->getAmount());
        $this->assertEquals(false, $cardDeck->hasEnoughCards());

        $cardDeck->removeCardFromDeck(0);
        $cardDeck->removeCardFromDeck(0);
        $this->assertEquals(0, $cardDeck->getAmount());

        $this->assertNull($cardDeck->drawCard());
        $this->assertEquals(0, count($cardDeck->getCards()));
    }
}
