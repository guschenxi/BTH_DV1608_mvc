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
    #[Route('/library/show/{bookid}', name: 'book_by_id')]
    public function showBookById(
        BookRepository $bookRepository,
        int $bookid
    ): Response {
        $book = $bookRepository
            ->find($bookid);
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
        $bookid = $request->request->get('book_id');
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($bookid);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$bookid
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Deleted book with id '.$bookid
        );
        return $this->redirectToRoute('book_show_all');
    }


    #[Route('/library/delete/{bookid}', name: 'book_delete_by_id', methods: ["POST"])]
    public function deleteBookById(
        ManagerRegistry $doctrine,
        int $bookid
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($bookid);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$bookid
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Deleted book with id '.$bookid
        );
        return $this->redirectToRoute('book_show_all');
    }
    #[Route('/library/update/{bookid}', name: 'book_update_by_id', methods: ["GET"])]
    public function bookUpdateById(
        BookRepository $bookRepository,
        int $bookid
    ): Response {
        $book = $bookRepository
            ->find($bookid);
        $data = [
            'book' => $book,
            ];
        return $this->render('book/update_book.html.twig', $data);
    }
    #[Route('/library/update/{bookid}', name: 'update_book_by_id', methods: ["POST"])]
    public function updateBookById(
        ManagerRegistry $doctrine,
        int $bookid,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($bookid);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$bookid
            );
        }

        $book->setTitle((string)$request->request->get('book_title'));
        $book->setIsbn((int)$request->request->get('book_isbn'));
        $book->setAuthor((string)$request->request->get('book_author'));
        $book->setImage((string)$request->request->get('book_image'));
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Updated details for book with id '.$bookid
        );
        return $this->redirectToRoute('book_show_all');
    }
}
