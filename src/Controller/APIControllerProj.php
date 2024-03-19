<?php

namespace App\Controller;

use App\Project\Card;
use App\Project\CardDeck;
use App\Project\CardHand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Exception;

class APIControllerProj extends AbstractController
{
    #[Route("/api/proj/game", name: "api_proj_game", methods: ['GET'])]
    public function apiProjGame(
        SessionInterface $session
    ): JsonResponse {

        $response = new JsonResponse($session->get("game")->jsonSerialize());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/proj/deck", name: "api_proj_deck", methods: ['GET'])]
    public function apiProjDeck(
        SessionInterface $session
    ): JsonResponse {

        $game = $session->get("game");

        $cards = $game->getDeck()->getCards();
        $output = [];
        foreach ($cards as $card) {
            $output[] = ["suit" => $card->getColor(), "rank" => $card->getNumber()];
        }

        $response = new JsonResponse($output);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/proj/deck/shuffle", name: "api_proj_deck_shuffle", methods: ['POST'])]
    public function apiProjDeckShuffle(
        SessionInterface $session
    ): JsonResponse {
    
        $game = $session->get("game");

        $deck = $game->getDeck();
        $deck->shuffleCards();
        $cards = $deck->getCards();

        $output = [];
        foreach ($cards as $card) {
            $output[] = ["suit" => $card->getColor(), "rank" => $card->getNumber()];
        }

        $response = new JsonResponse($output);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/proj/balance", name: "api_proj_balance", methods: ['GET'])]
    public function apiProjBalance(
        SessionInterface $session
    ): Response {
    
        $game = $session->get("game");
        $balance = $game->getPlayer()->getBalance();
        
        $response = new Response($balance);
        return $response;
    }
    #[Route("/api/proj/get_bets", name: "api_proj_get_bets", methods: ['GET'])]
    public function apiProjGetBets(
        SessionInterface $session
    ): JsonResponse {

        $game = $session->get("game");
        $bets = $game->getPlayer()->getBets();
        
        $response = new JsonResponse($bets);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/proj/balance/add/{num<\d+>}", name: "api_proj_add_balance", methods: ['POST'])]
    public function apiProjSetBalance(
    	int $num,
        SessionInterface $session
    ): JsonResponse {

        $game = $session->get("game");
        $newBalance = $game->getPlayer()->addBalance($num);
        
        $response = new JsonResponse($newBalance);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
