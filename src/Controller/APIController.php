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
use Exception;
use App\Repository\BookRepository;

class APIController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('/api.html.twig');
    }
    #[Route("/api/game", name: "api_game", methods: ['POST'])]
    public function apiGame(
        SessionInterface $session
    ): JsonResponse {

        $response = new JsonResponse($session->get("game")->jsonSerialize());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/library/books", name: "api_library_books", methods: ['GET', 'POST'])]
    public function apiBooks(
        BookRepository $bookRepository
    ): JsonResponse {
        $books = $bookRepository
            ->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/library/book/{isbn}", name: "api_library_book_isbn", methods: ['GET', 'POST'])]
    public function apiBookIsbn(
        BookRepository $bookRepository,
        int $isbn
    ): JsonResponse {
        $book = $bookRepository
            ->findOneBy(['isbn' => $isbn]);

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
