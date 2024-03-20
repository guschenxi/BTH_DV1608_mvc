<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class ProjCardHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateCardHand(): void
    {
        $cardHand = new CardHand();
        $this->assertInstanceOf("\App\Project\CardHand", $cardHand);

        $newCard = new Card("spade", "king");
        $newCard2 = new Card("heart", "queen");
        $newCard3 = new Card("heart", "ace");
        $cardHand->addCard($newCard);
        $cardHand->addCard($newCard2);
        $cardHand->addCard($newCard3);

        $this->assertEquals(3, $cardHand->getAmount());
        $this->assertIsArray($cardHand->getCards());
        $this->assertEquals(3, count($cardHand->getCards()));

        $this->assertEquals(10, $cardHand->changeNumberMax("king"));
        $this->assertEquals(10, $cardHand->changeNumberMax("queen"));
        $this->assertEquals(10, $cardHand->changeNumberMax("jack"));
        $this->assertEquals(11, $cardHand->changeNumberMax("ace"));
        $this->assertEquals(5, $cardHand->changeNumberMax("5"));

        $this->assertEquals(10, $cardHand->changeNumberMin("king"));
        $this->assertEquals(10, $cardHand->changeNumberMin("queen"));
        $this->assertEquals(10, $cardHand->changeNumberMin("jack"));
        $this->assertEquals(1, $cardHand->changeNumberMin("ace"));
        $this->assertEquals(2, $cardHand->changeNumberMin("2"));

        $this->assertEquals(21, $cardHand->getMinSum());
        $this->assertEquals(31, $cardHand->getMaxSum());

        $cardHand->cleanHand();
        $this->assertEmpty($cardHand->getCards());


    }
}
