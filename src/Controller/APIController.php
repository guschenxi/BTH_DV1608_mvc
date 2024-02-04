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
    
    #[Route("/api/deck", methods: ['GET'])]
    public function apiDeck
    (
        SessionInterface $session
    ): JsonResponse
    {
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

    #[Route("/api/deck/shuffle", methods: ['GET'])]
    public function apiDeckShuffle
    (
        SessionInterface $session
    ): JsonResponse
    {
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
    #[Route("/api/deck/shuffle", methods: ['POST'])]
    public function apiDeckShuffleCallBack(): JsonResponse
    {
        return $this->redirectToRoute('/api/deck/shuffle');
    }
    #[Route("/api/deck/draw", methods: ['GET'])]
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
    #[Route("/api/deck/draw/{num<\d+>}", methods: ['GET'])]
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
}

