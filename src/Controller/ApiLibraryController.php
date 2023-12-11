<?php

namespace App\Controller;

use App\Repository\LibraryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiLibraryController extends AbstractController
{
    #[Route("/api/library/books", name: "json_book")]
    public function jsonBook(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository
            ->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/library/book/{isbn}", name: "json_one_book")]
    public function jsonOneBook(
        LibraryRepository $libraryRepository,
        int $isbn
    ): Response {
        $book = $libraryRepository
            ->findBy([ 'isbn' => $isbn ]);

        if (empty($book)) {
            throw $this->createNotFoundException(
                'No book by specified isbn: '.$isbn
            );
        }

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
