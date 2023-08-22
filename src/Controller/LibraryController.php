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

        $library->setTitle($request->request->get('title'));
        $library->setIsbn($request->request->get('isbn'));
        $library->setAuthor($request->request->get('author'));
        $library->setCover($request->request->get('cover'));

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
        int $id
    ): Response {
        $book = $libraryRepository
            ->find($id);

        return $this->render('library/detail.html.twig', ['book' => $book]);
    }

    #[Route('/library/book/update/{id}', name: 'update_book_form')]
    public function updateBookForm(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository
            ->find($id);

        return $this->render('library/update.html.twig', ['book' => $book]);
    }

    #[Route('/library/book/update', name: 'update_book', methods: ['POST'])]
    public function updateBook(
        Request $request,
        LibraryRepository $libraryRepository
    ): Response {
        $book = $libraryRepository->find($request->request->get('id'));

        if (!$book) {
            throw $this->createNotFoundException(
                'No book by specified id: '.$id
            );
        }

        $book->setTitle($request->request->get('title'))
            ->setIsbn($request->request->get('isbn'))
            ->setAuthor($request->request->get('author'))
            ->setCover($request->request->get('cover'));

        $libraryRepository->save($book, true);

        return $this->redirectToRoute('book_index');
    }
}