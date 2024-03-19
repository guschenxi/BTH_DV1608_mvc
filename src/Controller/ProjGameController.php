<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Project\Game;

class ProjGameController extends AbstractController
{
    #[Route("/proj/new_game", name: "proj_game_play_new")]
    public function newGame(
        SessionInterface $session,
        Request $request
    ): Response {
        $session->remove('game');
        $playerName = $request->request->get('player_name');
        $numberOfHands = $request->request->get('player_hands');
        $bankBalance = $request->request->get('bank_balance');
        $game = new Game($playerName, $numberOfHands, $bankBalance);

        $session->set("game", $game);
        $session->set("startBalance", $bankBalance);
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
        $bets=[
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
        Request $request
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
    #[Route("/proj/player/draw", name: "proj_player_draw", methods: ["POST"])]
    public function playerDraw(
        SessionInterface $session,
        Request $request
    ): Response {
        $game = $session->get("game");
        $handNum = $request->request->get('hand_num');

        if (!$game->playerDraw($handNum)) {
            $this->addFlash(
                'notice',
                "Det finns inte tillräckligt kort kvar att dra."
            );
            return $this->redirectToRoute('proj_game_over');
        }

        $session->set("game", $game);
        return $this->redirectToRoute('proj_game_play');
    }
    #[Route("/proj/player/stay", name: "proj_player_stay", methods: ["POST"])]
    public function playerStay(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");

        $game->playerStay();
        if (!$game->playerStay()) {
            $this->addFlash(
                'notice',
                "Det finns inte tillräckligt kort kvar att dra."
            );
            return $this->redirectToRoute('proj_game_over');
        }

        $session->set("game", $game);
        return $this->redirectToRoute('proj_who_win');
    }
    #[Route("/proj/player/double", name: "proj_player_double", methods: ["POST"])]
    public function playerDouble(
        SessionInterface $session,
        Request $request
    ): Response {
        $game = $session->get("game");
        $handNum = $request->request->get('hand_num');
        
        $game->getPlayer()->doubleBets($handNum);
        $session->set("game", $game);
        return $this->redirectToRoute('proj_game_play');
    }
    #[Route("/proj/win", name: "proj_who_win")]
    public function whoWin(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");
        $winOrLose = $game->checkWin();
        $bets = $game->getPlayer()->getBets();
        $game->changeBalance($winOrLose, $bets);

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
}
