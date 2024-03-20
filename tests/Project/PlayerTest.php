<?php

namespace App\Project;

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
        $player = new Player("Sven", 2);
        $this->assertInstanceOf("\App\Project\Player", $player);
        $this->assertEquals("Sven", $player->getName());
        $this->assertEquals(0, $player->getBalance());

        $this->assertEquals(2, $player->getNumOfHands());

        $cardHand = $player->getCardHand(1);
        $this->assertInstanceOf("\App\Project\CardHand", $cardHand);

        $player->raiseBalance(5);
        $this->assertEquals(5, $player->getBalance());

        $player->raiseBalance(6);
        $this->assertEquals(11, $player->getBalance());

        $player->decreaseBalance(6);
        $this->assertEquals(5, $player->getBalance());

        $player->addBalance(6);
        $this->assertEquals(11, $player->getBalance());

        $this->assertIsArray($player->getCards());
        $this->assertIsArray($player->getMinSum());
        $this->assertIsArray($player->getMaxSum());

        $player->setBets([15, 20]);
        $this->assertEquals([15, 20], $player->getBets());
        $player->doubleBets(1);
        $this->assertEquals([15, 40], $player->getBets());

        $player->cleanHands();
        $this->assertEmpty($player->getCards()[0]);
        $this->assertEmpty($player->getCards()[1]);


    }
}
