<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/library/create', name: 'book_create', methods: ["GET"])]
    public function bookCreate(): Response
    {
        return $this->render('book/create.html.twig');
    }
    #[Route('/library/create', name: 'create_book', methods: ["POST"])]
    public function createBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle((string)$request->request->get('book_title'));
        $book->setIsbn((int)$request->request->get('book_isbn'));
        $book->setAuthor((string)$request->request->get('book_author'));
        $book->setImage((string)$request->request->get('book_image'));

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Saved new book with id '.$book->getId()
        );

        return $this->redirectToRoute('book_show_all');
    }
    #[Route('/library/show', name: 'book_show_all')]
    public function showAllBook(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();
        $data = [
            'books' => $books,
            ];
        return $this->render('book/all_books.html.twig', $data);
    }
    #[Route('/library/show/{id}', name: 'book_by_id')]
    public function showBookById(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);
        $data = [
            'book' => $book,
            ];
        return $this->render('book/show_book.html.twig', $data);
    }
    #[Route('/library/delete/', name: 'book_delete', methods: ["GET"])]
    public function deleteBook(): Response
    {
        return $this->render('book/delete_book.html.twig');
    }
    #[Route('/library/delete/', name: "delete_book", methods: ["POST"])]
    public function bookDelete(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $id = $request->request->get('book_id');
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Deleted book with id '.$id
        );
        return $this->redirectToRoute('book_show_all');
    }


    #[Route('/library/delete/{id}', name: 'book_delete_by_id', methods: ["POST"])]
    public function deleteBookById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Deleted book with id '.$id
        );
        return $this->redirectToRoute('book_show_all');
    }
    #[Route('/library/update/{id}', name: 'book_update_by_id', methods: ["GET"])]
    public function bookUpdateById(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);
        $data = [
            'book' => $book,
            ];
        return $this->render('book/update_book.html.twig', $data);
    }
    #[Route('/library/update/{id}', name: 'update_book_by_id', methods: ["POST"])]
    public function updateBookById(
        ManagerRegistry $doctrine,
        int $id,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $book->setTitle($request->request->get('book_title'));
        $book->setIsbn($request->request->get('book_isbn'));
        $book->setAuthor($request->request->get('book_author'));
        $book->setImage($request->request->get('book_image'));
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Updated details for book with id '.$id
        );
        return $this->redirectToRoute('book_show_all');
    }
}
