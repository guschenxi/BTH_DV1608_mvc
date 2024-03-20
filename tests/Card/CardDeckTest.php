<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardDeck.
 */
class ProjCardDeckTest extends TestCase
{
    public function testCreateCardDeck(): void
    {
        $cardDeck = new CardDeck();
        $this->assertInstanceOf("\App\Card\CardDeck", $cardDeck);
        $this->assertEquals(54, $cardDeck->getAmount());

        // same test for CardDeckNoJoker
        $cardDeck->shuffleCards();
        $this->assertInstanceOf("\App\Card\CardDeckNoJoker", $cardDeck);

        $this->assertIsArray($cardDeck->getCards());
        $this->assertEquals(true, $cardDeck->hasEnoughCards());

        $card = $cardDeck->drawCard();
        $this->assertInstanceOf("\App\Card\Card", $card);

        $cardDeck->removeCardFromDeck(0);
        $this->assertEquals(52, $cardDeck->getAmount());
        for ($i = 0; $i < 50; $i++) {
            $cardDeck->removeCardFromDeck(0);
        }
        $this->assertEquals(2, $cardDeck->getAmount());
        $this->assertEquals(false, $cardDeck->hasEnoughCards());

        $cardDeck->removeCardFromDeck(0);
        $cardDeck->removeCardFromDeck(0);
        $this->assertEquals(0, $cardDeck->getAmount());

        $this->assertNull($cardDeck->drawCard());
        $this->assertEquals(0, count($cardDeck->getCards()));
    }
}
