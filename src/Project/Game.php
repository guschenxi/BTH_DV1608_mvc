<?php

namespace App\Project;

use App\Project\CardDeckNoJoker;
use App\Project\CardDeck;
use App\Project\Card;
use App\Project\CardHand;
use App\Project\Player;

class Game
{
    protected CardDeckNoJoker $deck;
    protected Player $player;
    protected Player $bank;

    public function __construct(mixed $playerName, int $numOfHands, int $bank_balance)
    {
        $this->deck = new CardDeckNoJoker();
        $this->player = new Player(($playerName === "") ? "Spelare" : $playerName, $numOfHands, $bank_balance);
        $this->bank = new Player("SmartPC", 1, 0);
        $this->startGame();
    }
    public function startGame(): void
    {
        $this->deck->shuffleCards();
        //$this->player->addCard($this->deck->drawCard());
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
        if (!$drawnCard) {
            return false;
        }
        $this->player->getCardHand($handNum)->addCard($drawnCard);
        return true;
    }
    public function bankDraw(): bool
    {
    	$drawnCard = $this->deck->drawCard();
        if (!$drawnCard) {
            return false;
        }
        $this->bank->getCardHand(0)->addCard($drawnCard);
        return true;
    }
    public function playerStay(): bool
    {
        do {
            $this->bankDraw();
        } while ($this->bank->getCardHand(0)->getMinSum() < 17);
        return true;
    }
    public function whoWin(): Player
    {
        if ($this->player->getMinSum() > 21) {
            $winner = $this->bank;
            $winner->raiseScore();
            return $winner;
        }
        if ($this->player->getMinSum() == 21 || $this->player->getMaxSum() == 21) {
            $winner = $this->player;
            $winner->raiseScore();
            return $winner;
        }
        if ($this->bank->getMinSum() > 21) {
            $winner = $this->player;
            $winner->raiseScore();
            return $winner;
        }

        return $this->whoWinElse();
    }
    public function whoWinElse(): Player
    {
        $playerScore = $this->player->getMaxSum() <= 21 ? $this->player->getMaxSum() : $this->player->getMinSum();
        $bankScore = $this->bank->getMaxSum() <= 21 ? $this->bank->getMaxSum() : $this->bank->getMinSum();
        $winner = $bankScore >= $playerScore ? $this->bank : $this->player;
        $winner->raiseScore();
        return $winner;
    }
    public function nextRound(): bool
    {
        //clear hands
        $this->player->cleanHand();
        $this->bank->cleanHand();
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
    public function jsonSerialize(): mixed
    {
        return
        [
            'player' => $this->getPlayer(),
            'bank' => $this->getBank(),
            'deck' => $this->getDeck(),
        ];
    }
    public function getTotalScore(): string
    {
        $playerScore = $this->player->getScore();
        $bankScore = $this->bank->getScore();
        return " $playerScore  :  $bankScore ";
    }
    public function getFinalWinner(): ?Player
    {
        $playerScore = $this->player->getScore();
        $bankScore = $this->bank->getScore();
        if ($playerScore == $bankScore) {
            return null;
        }
        return $playerScore > $bankScore ? $this->player : $this->bank ;
    }
}
