<?php

namespace App\Project;

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
        $game = new Game("", 3, 100);
        $this->assertInstanceOf("\App\Project\Game", $game);
        $this->assertEquals('Spelare', $game->getPlayer()->getName());
        $game = new Game("Sven", 3, 100);
        $this->assertInstanceOf("\App\Project\Game", $game);
        $this->assertEquals('Sven', $game->getPlayer()->getName());
        $this->assertEquals('SmartPC', $game->getBank()->getName());

        $this->assertInstanceOf("\App\Project\CardDeckNoJoker", $game->getDeck());
        $this->assertInstanceOf("\App\Project\player", $game->getPlayer());
        $this->assertInstanceOf("\App\Project\player", $game->getBank());

        $this->assertEquals(true, $game->playerDraw(0));
        $this->assertEquals(3, $game->getPlayer()->getCardHand(0)->getAmount());
        $this->assertEquals(true, $game->playerDraw(0));
        $this->assertEquals(4, $game->getPlayer()->getCardHand(0)->getAmount());

        $this->assertEquals(true, $game->playerStay());
        $this->assertGreaterThan(0, $game->getBank()->getCardHand(0)->getAmount());

        $this->assertEquals(true, $game->nextRound());
        $this->assertEquals(0, $game->getPlayer()->getCardHand(0)->getAmount());
        $this->assertEquals(0, $game->getBank()->getCardHand(0)->getAmount());

        $restAmount = $game->getDeck()->getAmount();
        for ($i = 2; $i < $restAmount; $i++) {
            $game->playerDraw(0);
        }
        //$this->assertEquals(false, $game->playerDraw());
        $this->assertEquals(false, $game->nextRound());

        $game->playerDraw(0);
        $game->playerDraw(0);
        $this->assertEquals(false, $game->playerDraw(0));
        $this->assertEquals(false, $game->playerStay());

        $this->assertIsArray($game->jsonSerialize());
        $this->assertEquals(4, count($game->jsonSerialize()));
        $this->assertArrayHasKey("player", $game->jsonSerialize());
        $this->assertArrayHasKey("bank", $game->jsonSerialize());
        $this->assertArrayHasKey("deck", $game->jsonSerialize());
        $this->assertInstanceOf("\App\Project\Player", $game->jsonSerialize()["player"]);
        $this->assertInstanceOf("\App\Project\Player", $game->jsonSerialize()["bank"]);
        $this->assertInstanceOf("\App\Project\CardDeckNoJoker", $game->jsonSerialize()["deck"]);

        $this->assertIsArray($game->getDrawnCardDeck()->getAllDrawnCardsStat());
        $this->assertIsArray($game->getDrawnCardDeck()->getSuitedDrawnCardsStat());
        $this->assertArrayHasKey("heart", $game->getDrawnCardDeck()->getSuitedDrawnCardsStat());
        $this->assertIsArray($game->getDrawnCardDeck()->getRankedDrawnCardsStat());
        $this->assertArrayHasKey("jack", $game->getDrawnCardDeck()->getRankedDrawnCardsStat());
    }

    public function testGameScore(): void
    {
        $game = new Game("Sven", 2, 200);
        $game->getPlayer()->cleanHands();
        $game->getBank()->cleanHands();
        $game->getPlayer()->getCardHand(0)->addCard(new Card("heart", "ace")); //21
        $game->getPlayer()->getCardHand(0)->addCard(new Card("spade", "jack"));
        $game->getBank()->getCardHand(0)->addCard(new Card("heart", "jack"));
        $this->assertTrue($game->checkWin()[0]);

        $game = new Game("Sven", 2, 200);
        $game->getPlayer()->cleanHands();
        $game->getBank()->cleanHands();
        $game->getPlayer()->getCardHand(0)->addCard(new Card("heart", "9")); //19
        $game->getPlayer()->getCardHand(0)->addCard(new Card("spade", "jack"));
        $game->getBank()->getCardHand(0)->addCard(new Card("heart", "jack")); //20
        $game->getBank()->getCardHand(0)->addCard(new Card("spade", "jack"));
        $this->assertFalse($game->checkWin()[0]);

        $game = new Game("Sven", 2, 200);
        $game->getPlayer()->cleanHands();
        $game->getBank()->cleanHands();
        $game->getPlayer()->getCardHand(0)->addCard(new Card("heart", "queen")); //17
        $game->getPlayer()->getCardHand(0)->addCard(new Card("spade", "7"));
        $game->getBank()->getCardHand(0)->addCard(new Card("heart", "6")); //11
        $game->getBank()->getCardHand(0)->addCard(new Card("spade", "5"));
        $this->assertTrue($game->checkWin()[0]);

        $game = new Game("Sven", 2, 200);
        $game->getPlayer()->cleanHands();
        $game->getBank()->cleanHands();
        $game->getPlayer()->getCardHand(0)->addCard(new Card("heart", "queen")); // 17
        $game->getPlayer()->getCardHand(0)->addCard(new Card("spade", "7"));
        $game->getBank()->getCardHand(0)->addCard(new Card("heart", "6"));// 24
        $game->getBank()->getCardHand(0)->addCard(new Card("spade", "8"));
        $game->getBank()->getCardHand(0)->addCard(new Card("spade", "queen"));
        $winOrLose = $game->checkWin();
        $this->assertTrue($winOrLose[0]);

        $balance = $game->getPlayer()->getBalance();
        $game->changeBalance($winOrLose, [10, 20]);
        $newBalance = $game->getPlayer()->getBalance();
        $this->assertEquals($balance + 45, $newBalance);

        $game = new Game("Sven", 1, 200);
        $game->getPlayer()->cleanHands();
        $game->getBank()->cleanHands();
        $game->getPlayer()->getCardHand(0)->addCard(new Card("heart", "18")); //24
        $game->getPlayer()->getCardHand(0)->addCard(new Card("spade", "6"));
        $game->getBank()->getCardHand(0)->addCard(new Card("heart", "queen")); //15
        $game->getBank()->getCardHand(0)->addCard(new Card("spade", "5"));
        $winOrLose = $game->checkWin();
        $this->assertFalse($winOrLose[0]);

        $balance = $game->getPlayer()->getBalance();
        $game->changeBalance($winOrLose, [10, 20]);
        $newBalance = $game->getPlayer()->getBalance();
        $this->assertEquals($balance - 10, $newBalance);
    }
}
