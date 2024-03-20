<?php

namespace App\Project;

use App\Project\CardDeckNoJoker;
use App\Project\CardDeck;
use App\Project\Card;
use App\Project\CardHand;
use App\Project\Player;

class Game
{
    protected CardDeck $deck;
    protected DrawnCardDeck $drawnCardDeck;
    protected Player $player;
    protected Player $bank;

    public function __construct(mixed $playerName, int $numOfHands, int $bank_balance)
    {
        $this->deck = new CardDeck($numOfHands);
        $this->drawnCardDeck = new DrawnCardDeck;
        $this->player = new Player(($playerName === "") ? "Spelare" : $playerName, $numOfHands, $bank_balance);
        $this->bank = new Player("SmartPC", 1, 0);
        $this->startGame();
    }
    public function startGame(): void
    {
        $this->deck->shuffleCards();
        $this->newRound();
    }
    public function newRound(): void
    {
        $numOfHands = $this->getPlayer()->getNumOfHands();
        for ($i = 0; $i < $numOfHands; $i++) {
            $this->playerDraw($i);
            $this->playerDraw($i);
        }
        $this->bankDraw();
        $this->bankDraw();
        
    }
    public function playerDraw($handNum): bool
    {
        $drawnCard = $this->deck->drawCard();
        $this->drawnCardDeck->addCard($drawnCard);
        if (!$drawnCard) {
            return false;
        }
        $this->player->getCardHand($handNum)->addCard($drawnCard);
        return true;
    }
    public function bankDraw(): bool
    {
    	$drawnCard = $this->deck->drawCard();
        $this->drawnCardDeck->addCard($drawnCard);
        if (!$drawnCard) {
            return false;
        }
        $this->bank->getCardHand(0)->addCard($drawnCard);
        return true;
    }
    public function playerStay(): bool
    {
        while ($this->bank->getCardHand(0)->getMinSum() < 17) {
            if (!$this->bankDraw()) {
                return false;
            }
        }
        return true;
    }
    public function checkWin(): mixed
    {
        $playerMinSum = $this->player->getMinSum();
        $playerMaxSum = $this->player->getMaxSum();
        $bankMinSum = $this->bank->getCardHand(0)->getMinSum();
        $bankMaxSum = $this->bank->getCardHand(0)->getMaxSum();
        for ($i = 0; $i < $this->player->getNumOfHands(); $i++) {
            $output[] = $this->whoWin($playerMinSum[$i], $playerMaxSum[$i], $bankMinSum, $bankMaxSum);
        }
        return $output;
    }
    public function whoWin(int $playerMinSum, int $playerMaxSum, int $bankMinSum, int $bankMaxSum): bool
    {
        if ($playerMinSum > 21) {
            return false;
        }
        if ($playerMinSum == 21 || $playerMaxSum == 21) {
            return true;
        }
        if ($bankMinSum > 21) {
            return true;
        }
        return $this->whoWinElse($playerMinSum, $playerMaxSum, $bankMinSum, $bankMaxSum);
    }
    public function whoWinElse(int $playerMinSum, int $playerMaxSum, int $bankMinSum, int $bankMaxSum): bool
    {
        $playerScore = $playerMaxSum <= 21 ? $playerMaxSum : $playerMinSum;
        $bankScore = $bankMaxSum <= 21 ? $bankMaxSum : $bankMinSum;
        $winner = $bankScore >= $playerScore ? false : true;
        return $winner;
    }
    public function nextRound(): bool
    {
        //clear hands
        $this->player->cleanHands();
        $this->bank->cleanHands();
        return $this->deck->hasEnoughCards();
    }
    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getBank(): Player
    {
        return $this->bank;
    }
    public function getDeck(): CardDeckNoJoker
    {
        return $this->deck;
    }
    public function getDrawnCardDeck(): DrawnCardDeck
    {
        return $this->drawnCardDeck;
    }
    public function jsonSerialize(): mixed
    {
        return
        [
            'player' => $this->getPlayer(),
            'bank' => $this->getBank(),
            'deck' => $this->getDeck(),
            'drawnCardDeck' => $this->getDrawnCardDeck(),
        ];
    }
    public function changeBalance(array $winOrLose, array $bets): void
    {
		for ($i = 0; $i < count($winOrLose); $i++) {
			if ($winOrLose[$i]) {
			 $this->player->raiseBalance($bets[$i] * 1.5);
			}
			else {
			 $this->player->decreaseBalance($bets[$i]);
			}
		}
    }
}
