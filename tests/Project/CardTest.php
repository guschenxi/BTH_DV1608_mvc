<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class ProjCardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateCard(): void
    {
        $card = new Card('testSuit', 'testRank');
        $this->assertInstanceOf("\App\Project\Card", $card);
        $this->assertEquals(['testSuit', 'testRank'], $card->getValue());
        $this->assertEquals('testSuit', $card->getColor());
        $this->assertEquals('testRank', $card->getNumber());
        $this->assertNotEquals('test', $card->getNumber());
        $this->assertEquals('testSuit-testRank', $card->getAsString());
    }
}
