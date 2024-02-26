<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice(): void
    {
        $die = new Dice();
        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $res = $die->getValue();
        $this->assertNull($res);
        $this->assertEquals($die->getAsString(), "[]");
        
        $die = new DiceGraphic();
        $die->roll();
        $this->assertIsString($die->getAsString());
    }
    public function testRoll(): void
    {
        $die = new Dice();

        $res = $die->roll();
        $this->assertNotEmpty($res);
        $this->assertNotNull($res);
        $this->assertIsInt($res);
        
        $res = $die->getValue();
        $this->assertTrue($res >= 1);
        $this->assertTrue($res <= 6);

    }
    public function testDiceHand(): void
    {	
        $diceHand = new DiceHand();
        $diceHand->add(new Dice());
        $diceHand->add(new Dice());
        $diceHand->roll();
        $this->assertEquals($diceHand->getNumberDices(), 2);
        $this->assertEquals(count($diceHand->getValues()), 2);
        $this->assertEquals(count($diceHand->getString()), 2);
        
    }
}
