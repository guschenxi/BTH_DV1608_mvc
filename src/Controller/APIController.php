<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardDeck;
use App\Card\CardHand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class APIController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('/api.html.twig');
    }

    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function apiDeck(
        SessionInterface $session
    ): JsonResponse {
        $deck = new CardDeck();
        $cardHand = new CardHand();

        $session->set("deck", $deck);
        $session->set("cardHand", $cardHand);

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

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['POST'])]
    public function apiDeckShuffle(
        SessionInterface $session
    ): JsonResponse {
        $deck = new CardDeck();
        $cardHand = new CardHand();
        $deck->shuffleCards();
        $cards = $deck->getCards();

        $session->set("deck", $deck);
        $session->set("cardHand", $cardHand);

        $output = [];
        foreach ($cards as $card) {
            $output[] = ["suit" => $card->getColor(), "rank" => $card->getNumber()];
        }

        $response = new JsonResponse($output);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
        //return $this->json(['deck' => $cards]);
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ['POST'])]
    public function apiDrawCard(
        SessionInterface $session
    ): Response {
        // read session
        $deck = $session->get("deck");
        $cardHand = $session->get("cardHand");

        // get random card
        $drawnCard = $deck->drawCard();

        // put the card in hand
        $cardHand->addCard($drawnCard);

        // write to session
        $session->set("deck", $deck);
        $session->set("cardHand", $cardHand);


        $output[] = ["suit" => $drawnCard->getColor(), "rank" => $drawnCard->getNumber()];


        $data = [
            'drawnCards' => $output,
            'remainingCards' => count($deck->getCards()),
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/deck/draw/{num<\d+>}", name: "api_deck_draw_num", methods: ['POST'])]
    public function apiDrawCards(
        int $num,
        SessionInterface $session
    ): Response {
        // read session
        $deck = $session->get("deck");
        $cardHand = $session->get("cardHand");

        if ($num > $deck->getAmount()) {
            throw new \Exception("Can not draw so many cards! Not enough cards left");
        }

        for ($i = 1; $i <= $num; $i++) {
            $drawnCard = $deck->drawCard();
            $cardHand->addCard($drawnCard);
        }

        // write to session
        $session->set("deck", $deck);
        $session->set("cardHand", $cardHand);

        $drawnCards = array_slice($cardHand->getCards(), -$num);
        $output = [];
        foreach ($drawnCards as $drawnCard) {
            $output[] = ["suit" => $drawnCard->getColor(), "rank" => $drawnCard->getNumber()];
        }
        $data = [
            'drawnCards' => $output,
            'remainingCards' => count($deck->getCards()),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/deck/deal/{players<\d+>}/{cards<\d+>}", name: "api_deal_cards")]
    public function apiDealCards(
        int $players,
        int $cards,
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");

        $playerHands = [];
        for ($i = 1; $i <= $players; $i++) {
            $playerHands[] = [];
        }

        for ($cardCount = 0; $cardCount < $cards; $cardCount++) {
            for ($player = 0; $player < $players; $player++) {
                $card = $deck->drawCard();
                $playerHands[$player][] = ["suit" => $card->getColor(), "rank" => $card->getNumber()];
            }
        }

        $remainingCards = $deck->getAmount();

        $data = [
            'playerHands' => $playerHands,
            'remainingCards' => $remainingCards,
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
