<?php

namespace App\Card;

use App\Card\CardDeckNoJoker;
use App\Card\CardDeck;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\Player;

class Game
{
    protected CardDeckNoJoker $deck;
    protected Player $player;
    protected Player $bank;

    public function __construct(mixed $playerName)
    {
        $this->deck = new CardDeckNoJoker();
        $this->player = new Player(($playerName === "") ? "Spelare" : $playerName);
        $this->bank = new Player("SmartPC");
        $this->startGame();
    }
    public function startGame(): void
    {
        $this->deck->shuffleCards();
        //$this->player->addCard($this->deck->drawCard());
    }
    public function playerDraw(): void
    {
        $this->player->addCard($this->deck->drawCard());
    }
    public function playerStay(): void
    {
        do {
            $this->bank->addCard($this->deck->drawCard());
        } while ($this->bank->getMinSum() < 17);
    }
    public function whoWin(): object
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
        $playerScore = $this->player->getMaxSum() <= 21 ? $this->player->getMaxSum() : $this->player->getMinSum();
        $bankScore = $this->bank->getMaxSum() <= 21 ? $this->bank->getMaxSum() : $this->bank->getMinSum();
        $winner = $bankScore >= $playerScore ? $this->bank : $this->player;
        $winner->raiseScore();
        return $winner;
    }
    public function nextRound(): void
    {
        //clear hands
        $this->player->cleanHand();
        $this->bank->cleanHand();
    }
    public function getPlayer(): object
    {
        return $this->player;
    }

    public function getBank(): object
    {
        return $this->bank;
    }
    public function getDeck(): object
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
        return "{ $playerScore } : { $bankScore }";
    }
}
