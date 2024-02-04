<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;
use App\Card\CardDeck;
use App\Card\CardHand;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
    
        return $this->render('card/home.html.twig');
    }
    #[Route("/card/deck", name: "card_deck")]
    public function card_deck(
        SessionInterface $session    
    ): Response
    {
        $deck = new CardDeck();
        $cardHand = new CardHand();

        // write to session
        $session->set("deck", $deck);
        $session->set("cardHand", $cardHand);
        
        $this->addFlash(
                    'notice',
                    'New card deck created.'
                );
                
        $data = ['deck' => $deck->getCards(),];
		return $this->render('card/deck.html.twig', $data);
    }
    #[Route("/card/shuffle", name: "card_shuffle")]
    public function card_shuffle(
        SessionInterface $session
    ): Response
    {
        $deck = new CardDeck();
        $cardHand = new CardHand();
        $deck->shuffleCards();
        
        // write to session
        $session->set("deck", $deck);
        $session->set("cardHand", $cardHand);
        
		$this->addFlash(
		    'notice',
		    'New card deck created and shuffled.'
		);
		
        $data = ['deck' => $deck->getCards(),];
        return $this->render('card/deck.html.twig', $data);
    }
    #[Route("/card/draw", name: "card_draw")]
    public function drawCard(
        SessionInterface $session
    ): Response
    {
        // read session
    	$deck = $session->get("deck");
    	$cardHand = $session->get("cardHand");
        
        // get random card
        $randomIndex = array_rand($deck->getCards());
        $drawnCard = $deck->getCards()[$randomIndex];
        
        // put the card in hand
        $cardHand->addCard($drawnCard);
        
        // delete drawn card from deck
        $deck->removeCardFromDeck($randomIndex);
        
        // write to session
        $session->set("deck", $deck);
        $session->set("cardHand", $cardHand);
        
        $this->addFlash(
            'notice',
            'One card is drawn.'
        );
        
        $data = [
            'drawnCards' => [$drawnCard],
            'remainingCards' => count($deck->getCards()),
            'cardsInHand' => $cardHand->getAmount(),
        ];
        return $this->render('card/draw_many.html.twig', $data);
    }
    #[Route("/card/draw/{num<\d+>}", name: "card_num_draw")]
    public function drawCards(
    	int $num,
        SessionInterface $session
    ): Response
    {
        // read session
    	$deck = $session->get("deck");
    	$cardHand = $session->get("cardHand");
    	
        if ($num > $deck->getAmount()) {
            throw new \Exception("Can not draw so many cards! Not enough cards left");
        }
        //$drawnCards = [];
        for ($i = 1; $i <= $num; $i++) {
            $randomIndex = array_rand($deck->getCards());
            //$drawnCards[] = $deck->getCards()[$randomIndex];
            $cardHand->addCard($deck->getCards()[$randomIndex]);
            $deck->removeCardFromDeck($randomIndex);
        }

        // write to session
        $session->set("deck", $deck);
        $session->set("cardHand", $cardHand);
        
        $this->addFlash(
            'notice',
            "{$num} card(s) is/are drawn."
        );
        
        $data = [
            //'drawnCards' => $drawnCards,
            'drawnCards' => array_slice($cardHand->getCards(), -$num),
            'remainingCards' => count($deck->getCards()),
            'cardsInHand' => $cardHand->getAmount(),
        ];

        return $this->render('card/draw_many.html.twig', $data);
    }
}
