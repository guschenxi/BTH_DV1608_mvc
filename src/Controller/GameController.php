<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Game;

class GameController extends AbstractController
{
    #[Route("/game", name: "game_start")]
    public function gameHome(
        SessionInterface $session
    ): Response
    {
        return $this->render('game/home.html.twig');
    }
    #[Route("/game/doc", name: "game_doc")]
    public function gameDoc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
    #[Route("/game/play_new", name: "game_play_new")]
    public function playNew(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $session->remove('game');
        $playerName = $request->request->get('player_name');
        $game = new Game($playerName);
        
        $session->set("game", $game);
        return $this->redirectToRoute('game_play');
    }
    #[Route("/game/play", name: "game_play")]
    public function playGame(
        SessionInterface $session
    ): Response
    {
        $game = $session->get("game");
        $playerMinSum = $game->getPlayer()->getMinSum();
        $playerMaxSum = $game->getPlayer()->getMaxSum();
        
        $data = [
            'player' => $game->getPlayer()->getCards(),
            'playerMinSum' => $playerMinSum,
            'playerMaxSum' => $game->getPlayer()->getMaxSum(),
            'playerName' => $game->getPlayer()->getName(),
            'playerScore' => $game->getPlayer()->getScore(),
            'bank' => $game->getBank()->getCards(),
            'bankMinSum' => $game->getBank()->getMinSum(),
            'bankMaxSum' => $game->getBank()->getMaxSum(),
            //'bankName' => $game->getbank()->getName(),
            'bankScore' => $game->getbank()->getScore(),
            'deck' => $game->getDeck()->getCards(),
            'totalScore' => $game->getTotalScore()
        ];

        $session->set("game", $game);
        if ($playerMinSum >= 21 || $playerMaxSum == 21) {
        	return $this->redirectToRoute('who_win');
        }
        return $this->render('game/play.html.twig', $data);
    }
    #[Route("/game/player/draw", name: "player_draw", methods: ["POST"])]
    public function playerDraw(
        SessionInterface $session
    ): Response
    {
        $game = $session->get("game");
        
        $game->playerDraw();
        
        $session->set("game", $game);
        return $this->redirectToRoute('game_play');
    }
        #[Route("/game/player/stay", name: "player_stay", methods: ["POST"])]
    public function playerStay(
        SessionInterface $session
    ): Response
    {
        $game = $session->get("game");
        
        $game->playerStay();
        
        $session->set("game", $game);
        return $this->redirectToRoute('who_win');
    }
        #[Route("/game/win", name: "who_win")]
    public function whoWin(
        SessionInterface $session
    ): Response
    {
        $game = $session->get("game");
        
        $who_win = $game->whoWin();
        
        $data = [
            'who_win' => $who_win->getName(),
            'player' => $game->getPlayer()->getCards(),
            'playerMinSum' => $game->getPlayer()->getMinSum(),
            'playerMaxSum' => $game->getPlayer()->getMaxSum(),
            'playerName' => $game->getPlayer()->getName(),
            'playerScore' => $game->getPlayer()->getScore(),
            'bank' => $game->getBank()->getCards(),
            'bankMinSum' => $game->getBank()->getMinSum(),
            'bankMaxSum' => $game->getBank()->getMaxSum(),
            //'bankName' => $game->getbank()->getName(),
            'bankScore' => $game->getbank()->getScore(),
        ];
        
        $game->nextRound();
        $session->set("game", $game);
        return $this->render('game/who_win.html.twig', $data);
    }
}
