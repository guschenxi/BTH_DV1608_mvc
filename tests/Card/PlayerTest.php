<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class PlayerTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreatePlayer(): void
    {
        $player = new Player("Sven");
        $this->assertInstanceOf("\App\Card\Player", $player);
        $this->assertEquals("Sven", $player->getName());
        $this->assertEquals(0, $player->getScore());

        $player->raiseScore();
        $this->assertEquals(1, $player->getScore());

        $player->raiseScore();
        $this->assertEquals(2, $player->getScore());


        // same test for CardHand
        $newCard = new Card("spade", "king");
        $newCard2 = new Card("heart", "queen");
        $newCard3 = new Card("heart", "ace");
        $player->addCard($newCard);
        $player->addCard($newCard2);
        $player->addCard($newCard3);

        $this->assertEquals(3, $player->getAmount());
        $this->assertIsArray($player->getCards());
        $this->assertEquals(3, count($player->getCards()));

        $this->assertEquals(13, $player->changeNumberMax("king"));
        $this->assertEquals(12, $player->changeNumberMax("queen"));
        $this->assertEquals(11, $player->changeNumberMax("jack"));
        $this->assertEquals(14, $player->changeNumberMax("ace"));
        $this->assertEquals(5, $player->changeNumberMax("5"));

        $this->assertEquals(13, $player->changeNumberMin("king"));
        $this->assertEquals(12, $player->changeNumberMin("queen"));
        $this->assertEquals(11, $player->changeNumberMin("jack"));
        $this->assertEquals(1, $player->changeNumberMin("ace"));
        $this->assertEquals(2, $player->changeNumberMin("2"));

        $this->assertEquals(26, $player->getMinSum());
        $this->assertEquals(39, $player->getMaxSum());

        $player->cleanHand();
        $this->assertEmpty($player->getCards());


    }
}
