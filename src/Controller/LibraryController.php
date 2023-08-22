<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library_index')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/add', name: 'add_book_form', methods: ['GET'])]
    public function addBookForm(): Response
    {
        return $this->render('library/add.html.twig');
    }

    #[Route('/library/add', name: 'add_book', methods: ['POST'])]
    public function addBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();

        $library = new Library();

        $library->setTitle((string) $request->request->get('title'));
        $library->setIsbn((int) $request->request->get('isbn'));
        $library->setAuthor((string) $request->request->get('author'));
        $library->setCover((string) $request->request->get('cover'));

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($library);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('book_index');
    }

    #[Route('/library/book', name: 'book_index')]
    public function manyBook(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository
            ->findAll();

        return $this->render('library/book.html.twig', ['books' => $books]);
    }

    #[Route('/library/book/detail/{id}', name: 'book_detail')]
    public function oneBook(
        LibraryRepository $libraryRepository,
        int $bookId
    ): Response {
        $book = $libraryRepository
            ->find($bookId);

        return $this->render('library/detail.html.twig', ['book' => $book]);
    }

    #[Route('/library/book/update/{id}', name: 'update_book_form')]
    public function updateBookForm(
        LibraryRepository $libraryRepository,
        int $bookId
    ): Response {
        $book = $libraryRepository
            ->find($bookId);

        return $this->render('library/update.html.twig', ['book' => $book]);
    }

    #[Route('/library/book/update', name: 'update_book', methods: ['POST'])]
    public function updateBook(
        Request $request,
        LibraryRepository $libraryRepository
    ): Response {
        $bookId = $request->request->get('id');
        $book = $libraryRepository->find($bookId);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book by specified id: '.$bookId
            );
        }

        $book->setTitle((string) $request->request->get('title'))
            ->setIsbn((int) $request->request->get('isbn'))
            ->setAuthor((string) $request->request->get('author'))
            ->setCover((string) $request->request->get('cover'));

        $libraryRepository->save($book, true);

        return $this->redirectToRoute('book_index');
    }

    #[Route('/library/book/delete/{id}', name: 'delete_book_confirm')]
    public function deleteBookConfirm(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book by specified id: '.$id
            );
        }

        return $this->render('library/delete.html.twig', ['book' => $book]);
    }

    #[Route('/library/book/delete', name: 'delete_book', methods: ['POST'])]
    public function deleteBook(
        Request $request,
        LibraryRepository $libraryRepository,
    ): Response {
        $id = $request->request->get('id');
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book by specified id: '.$id
            );
        }

        $libraryRepository->remove($book, true);

        return $this->redirectToRoute('book_index');
    }
}
