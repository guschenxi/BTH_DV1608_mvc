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
use App\Repository\GamelogRepository;
use App\Repository\RoundlogRepository;

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
    ): Response {

        $game = $session->get("game");
        $newBalance = $game->getPlayer()->addBalance($num);

        $response = new Response($newBalance);
        return $response;
    }
    #[Route("/api/proj/drawn_cards", name: "api_proj_drawn_cards", methods: ['GET'])]
    public function apiProjDrawnCards(
        SessionInterface $session
    ): Response {

        $game = $session->get("game");
        $drawnCards = $game->getDrawnCardDeck()->getCards();

        $response = new JsonResponse($drawnCards);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/proj/stat/all", name: "api_proj_stat_all", methods: ['GET'])]
    public function apiProjAllDrawnCardStat(
        SessionInterface $session
    ): Response {

        $game = $session->get("game");
        $drawnCards = $game->getDrawnCardDeck()->getAllDrawnCardsStat();

        $response = new JsonResponse($drawnCards);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/proj/stat/ranked", name: "api_proj_stat_ranked", methods: ['GET'])]
    public function apiProjRankedDrawnCardStat(
        SessionInterface $session
    ): Response {

        $game = $session->get("game");
        $drawnCards = $game->getDrawnCardDeck()->getRankedDrawnCardsStat();

        $response = new JsonResponse($drawnCards);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/proj/stat/suited", name: "api_proj_stat_suited", methods: ['GET'])]
    public function apiProjSuitedDrawnCardStat(
        SessionInterface $session
    ): Response {

        $game = $session->get("game");
        $drawnCards = $game->getDrawnCardDeck()->getSuitedDrawnCardsStat();

        $response = new JsonResponse($drawnCards);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route('/api/proj/gamelog', name: 'api_proj_gamelog')]
    public function showGameLog(
        GamelogRepository $gamelogRepository
    ): Response {
        $gamelog = $gamelogRepository
            ->findAll();
        $response = $this->json($gamelog);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route('/api/proj/roundlog', name: 'api_proj_roundlog')]
    public function showRoundLog(
        RoundlogRepository $roundlogRepository
    ): Response {
        $roundlog = $roundlogRepository
            ->findAll();
        $response = $this->json($roundlog);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
