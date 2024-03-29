<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardDeckNoJoker.
 */
class ProjCardDeckNoJokerTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateCardDeckNoJoker(): void
    {
        $cardDeck = new CardDeckNoJoker();
        $cardDeck->shuffleCards();
        $this->assertInstanceOf("\App\Project\CardDeckNoJoker", $cardDeck);
        $this->assertEquals(52, $cardDeck->getAmount());
        $this->assertIsArray($cardDeck->getCards());
        $this->assertEquals(true, $cardDeck->hasEnoughCards());
        $card = $cardDeck->drawCard();
        $this->assertInstanceOf("\App\Project\Card", $card);

        $cardDeck->removeCardFromDeck(0);
        $this->assertEquals(50, $cardDeck->getAmount());
        for ($i = 0; $i < 48; $i++) {
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
