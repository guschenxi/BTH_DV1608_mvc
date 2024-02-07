<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class GameTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateGame(): void
    {
        $game = new Game("");
        $this->assertInstanceOf("\App\Card\Game", $game);
        $this->assertEquals('Spelare', $game->getPlayer()->getName());
        $game = new Game("Sven");
        $this->assertInstanceOf("\App\Card\Game", $game);
        $this->assertEquals('Sven', $game->getPlayer()->getName());
        $this->assertEquals('SmartPC', $game->getBank()->getName());

        $this->assertInstanceOf("\App\Card\CardDeckNoJoker", $game->getDeck());
        $this->assertInstanceOf("\App\Card\player", $game->getPlayer());
        $this->assertInstanceOf("\App\Card\player", $game->getBank());

        $this->assertEquals(" 0  :  0 ", $game->getTotalScore());
        $this->assertNull($game->getFinalWinner());

        $this->assertEquals(true, $game->playerDraw());
        $this->assertEquals(1, $game->getPlayer()->getAmount());
        $this->assertEquals(true, $game->playerDraw());
        $this->assertEquals(2, $game->getPlayer()->getAmount());

        $this->assertEquals(true, $game->playerStay());
        $this->assertGreaterThan(0, $game->getBank()->getAmount());

        $this->assertEquals(true, $game->nextRound());
        $this->assertEquals(0, $game->getPlayer()->getAmount());
        $this->assertEquals(0, $game->getBank()->getAmount());

        $restAmount = $game->getDeck()->getAmount();
        for ($i = 2; $i < $restAmount; $i++) {
            $game->playerDraw();
        }
        //$this->assertEquals(false, $game->playerDraw());
        $this->assertEquals(false, $game->nextRound());

        $game->playerDraw();
        $game->playerDraw();
        $this->assertEquals(false, $game->playerDraw());
        $this->assertEquals(false, $game->playerStay());

        $this->assertIsArray($game->jsonSerialize());
        $this->assertEquals(3, count($game->jsonSerialize()));
        $this->assertArrayHasKey("player", $game->jsonSerialize());
        $this->assertArrayHasKey("bank", $game->jsonSerialize());
        $this->assertArrayHasKey("deck", $game->jsonSerialize());
        $this->assertInstanceOf("\App\Card\Player", $game->jsonSerialize()["player"]);
        $this->assertInstanceOf("\App\Card\Player", $game->jsonSerialize()["bank"]);
        $this->assertInstanceOf("\App\Card\CardDeckNoJoker", $game->jsonSerialize()["deck"]);
    }

    public function testGameScore(): void
    {
        $game = new Game("Sven");
        $game->getPlayer()->addCard(new Card("heart", "10"));
        $game->getPlayer()->addCard(new Card("spade", "jack"));
        $game->getBank()->addCard(new Card("heart", "jack"));
        $this->assertEquals($game->getPlayer(), $game->whoWin());
        $this->assertEquals(" 1  :  0 ", $game->getTotalScore());
        $this->assertEquals($game->getPlayer(), $game->getFinalWinner());

        $game = new Game("Sven");
        $game->getPlayer()->addCard(new Card("heart", "9"));
        $game->getPlayer()->addCard(new Card("spade", "jack"));
        $game->getBank()->addCard(new Card("heart", "jack"));
        $game->getBank()->addCard(new Card("spade", "jack"));
        $this->assertEquals($game->getPlayer(), $game->whoWin());
        $this->assertEquals(" 1  :  0 ", $game->getTotalScore());
        $this->assertEquals($game->getPlayer(), $game->getFinalWinner());

        $game = new Game("Sven");
        $game->getPlayer()->addCard(new Card("heart", "jack"));
        $game->getPlayer()->addCard(new Card("spade", "king"));
        $game->getBank()->addCard(new Card("heart", "8"));
        $game->getBank()->addCard(new Card("spade", "10"));
        $this->assertEquals($game->getBank(), $game->whoWin());
        $this->assertEquals(" 0  :  1 ", $game->getTotalScore());
        $this->assertEquals($game->getbank(), $game->getFinalWinner());

        $game = new Game("Sven");
        $game->getPlayer()->addCard(new Card("heart", "queen"));
        $game->getPlayer()->addCard(new Card("spade", "7"));
        $game->getBank()->addCard(new Card("heart", "ace"));
        $game->getBank()->addCard(new Card("spade", "6"));
        $this->assertEquals($game->getBank(), $game->whoWin());
        $this->assertEquals(" 0  :  1 ", $game->getTotalScore());
        $this->assertEquals($game->getbank(), $game->getFinalWinner());

        $game = new Game("Sven");
        $game->getPlayer()->addCard(new Card("heart", "ace"));
        $game->getPlayer()->addCard(new Card("spade", "6"));
        $game->getBank()->addCard(new Card("heart", "queen"));
        $game->getBank()->addCard(new Card("spade", "5"));
        $this->assertEquals($game->getPlayer(), $game->whoWin());
        $this->assertEquals(" 1  :  0 ", $game->getTotalScore());
        $this->assertEquals($game->getPlayer(), $game->getFinalWinner());
    }
}
