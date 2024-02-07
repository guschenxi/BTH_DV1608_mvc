<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardDeckTest extends TestCase
{
    public function testCreateCardDeck()
    {
        $cardDeck = new CardDeck();
        $cardDeckShuffled = $cardDeck->shuffleCards();
        $this->assertInstanceOf("\App\Card\CardDeckNoJoker", $cardDeck);
        $this->assertInstanceOf("\App\Card\CardDeck", $cardDeck);
        $this->assertEquals(54, $cardDeck->getAmount());
        $this->assertIsArray($cardDeck->getCards());
        $this->assertNotEquals($cardDeck, $cardDeckShuffled);
        $this->assertEquals(true, $cardDeck->hasEnoughCards());
        
        $this->assertContains(["red","joker"], $cardDeck->getCards());
        $this->assertContains(["black","joker"], $cardDeck->getCards());
        
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
