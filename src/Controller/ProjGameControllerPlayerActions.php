<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Project\Game;

class ProjGameControllerPlayerActions extends AbstractController
{
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
}
