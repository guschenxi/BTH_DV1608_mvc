<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Project\Game;
use App\Entity\Gamelog;
use App\Entity\Roundlog;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;

class ProjGameController extends AbstractController
{
    #[Route("/proj/new_game", name: "proj_game_play_new")]
    public function newGame(
        SessionInterface $session,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $session->remove('game');
        $playerName = $request->request->get('player_name');
        $numberOfHands = (int)$request->request->get('player_hands');
        $bankBalance = (int)$request->request->get('bank_balance');
        $game = new Game($playerName, $numberOfHands, $bankBalance);

        // write into database
        $entityManager = $doctrine->getManager();
        $gamelog = new Gamelog();
        $gamelog->setName($playerName ? (string)$playerName : "Spelare");
        $gamelog->setHands($numberOfHands);
        $gamelog->setBalance($bankBalance);
        $entityManager->persist($gamelog);
        $entityManager->flush();
        $gamelogId = $gamelog->getId();

        $session->set("game", $game);
        $session->set("startBalance", $bankBalance);
        $session->set("gamelogId", $gamelogId);
        return $this->redirectToRoute('proj_enter_bets');
    }
    #[Route("/proj/enter_bets", name: "proj_enter_bets")]
    public function enterBets(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");

        $data = [
            'numOfHands' => $game->getPlayer()->getNumOfHands(),
            'playerName' => $game->getPlayer()->getName(),
            'playerBalance' => $game->getPlayer()->getBalance()
        ];

        return $this->render('proj/bets.html.twig', $data);
    }
    #[Route("/proj/set_bets", name: "proj_set_bets")]
    public function setBets(
        SessionInterface $session,
        Request $request
    ): Response {
        $game = $session->get("game");
        $bets = [
            $request->request->get('bets_0'),
            $request->request->get('bets_1'),
            $request->request->get('bets_2')
        ];
        $game->getPlayer()->setBets($bets);
        $session->set("game", $game);
        return $this->redirectToRoute('proj_game_play');
    }
    #[Route("/proj/play", name: "proj_game_play")]
    public function playGame(
        SessionInterface $session,
    ): Response {
        $game = $session->get("game");

        $data = [
            'numOfHands' => $game->getPlayer()->getNumOfHands(),
            'playerName' => $game->getPlayer()->getName(),
            'playerBalance' => $game->getPlayer()->getBalance(),
            'bets' => $game->getPlayer()->getBets(),
            'playerHands' => $game->getPlayer()->getCards(),
            'playerMinSum' => $game->getPlayer()->getMinSum(),
            'playerMaxSum' => $game->getPlayer()->getMaxSum(),
            'bank' => $game->getBank()->getCardHand(0)->getCards(),
            'bankMinSum' => $game->getBank()->getCardHand(0)->getMinSum(),
            'bankMaxSum' => $game->getBank()->getCardHand(0)->getMaxSum(),
            //'bankName' => $game->getbank()->getName(),
            //'bankScore' => $game->getbank()->getScore(),
            'deck' => $game->getDeck()->getCards(),
            //'totalScore' => $game->getTotalScore()
        ];

        $session->set("game", $game);
        return $this->render('proj/play.html.twig', $data);
    }

    #[Route("/proj/win", name: "proj_who_win")]
    public function whoWin(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ): Response {
        $game = $session->get("game");
        $gamelogId = $session->get("gamelogId");
        $currentBalance = $game->getPlayer()->getBalance();
        $winOrLose = $game->checkWin();
        $bets = $game->getPlayer()->getBets();
        $game->changeBalance($winOrLose, $bets);
        $newBalance = $game->getPlayer()->getBalance();

        // write into database
        $entityManager = $doctrine->getManager();
        $roundlog = new Roundlog();
        $roundlog->setGamelogId($gamelogId);
        $roundlog->setWinhands(count(array_filter($winOrLose)));
        $roundlog->setDifference($newBalance - $currentBalance);
        $roundlog->setNewbalance($newBalance);
        $entityManager->persist($roundlog);
        $entityManager->flush();

        $session->set("game", $game);
        //$session->set("winOrLose", $winOrLose)
        return $this->redirectToRoute('proj_result');
    }
    #[Route("/proj/result", name: "proj_result")]
    public function showWhoWin(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");
        $winOrLose = $game->checkWin();
        $bets = $game->getPlayer()->getBets();
        $data = [
            'numOfHands' => $game->getPlayer()->getNumOfHands(),
            'winOrLose' => $winOrLose,
            'bets' => $bets,
            'playerHands' => $game->getPlayer()->getCards(),
            'playerMinSum' => $game->getPlayer()->getMinSum(),
            'playerMaxSum' => $game->getPlayer()->getMaxSum(),
            'playerName' => $game->getPlayer()->getName(),
            'playerBalance' => $game->getPlayer()->getBalance(),
            'bank' => $game->getBank()->getCardHand(0)->getCards(),
            'bankMinSum' => $game->getBank()->getCardHand(0)->getMinSum(),
            'bankMaxSum' => $game->getBank()->getCardHand(0)->getMaxSum(),

        ];
        return $this->render('proj/who_win.html.twig', $data);
    }
    #[Route("/proj/next_round", name: "proj_next_round")]
    public function nextRound(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");
        $nextRound = $game->nextRound();

        if (!$nextRound) {
            $this->addFlash(
                'notice',
                "Antal kort räcker inte till nästa runda."
            );
            return $this->redirectToRoute('proj_game_over');
        }
        $game->newRound();
        $session->set("game", $game);
        return $this->redirectToRoute('proj_enter_bets');
    }
    #[Route("/proj/game_over", name: "proj_game_over")]
    public function gameOver(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");

        $data = [
            'playerBalance' => $game->getPlayer()->getBalance(),
            'playerName' => $game->getPlayer()->getName(),
            'startBalance' => $session->get("startBalance")
            ];
        return $this->render('proj/game_over.html.twig', $data);
    }
    #[Route("/proj/stat", name: "proj_stat")]
    public function stat(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");
        //$statistics = $game->getDrawnCardDeck()->getAllDrawnCardsStat();
        $rankStatistics = $game->getDrawnCardDeck()->getRankedDrawnCardsStat();
        $suitStatistics = $game->getDrawnCardDeck()->getSuitedDrawnCardsStat();
        $data = [
            'rankStatistics' => $rankStatistics,
            'suitStatistics' => $suitStatistics,
            'drawnCardDeck' => $game->getDrawnCardDeck()->getCards(),
        ];
        return $this->render('proj/stat.html.twig', $data);
    }
}
