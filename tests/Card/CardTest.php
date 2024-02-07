<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateCard()
    {
        $card = new Card('testSuit', 'testRank');
        $this->assertInstanceOf("\App\Card\Card", $card);
        $this->assertEquals(['testSuit', 'testRank'], $card->getValue());
        $this->assertEquals('testSuit', $card->getColor());
        $this->assertEquals('testRank', $card->getNumber());
        $this->assertNotEquals('test', $card->getNumber());
        $this->assertEquals('testSuit-testRank', $card->getAsString());
    }
}
