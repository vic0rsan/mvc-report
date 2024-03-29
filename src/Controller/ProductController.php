<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/create', name: 'product_create')]
    public function createProduct(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard_num_' . rand(1, 9));
        $product->setValue(rand(100, 999));

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    #[Route('/product/show', name: 'product_show_all')]
    public function showAllProduct(
        ProductRepository $productRepository
    ): Response {
        $products = $productRepository
            ->findAll();

        $response = $this->json($products);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/product/show/{id}', name: 'product_by_id')]
    public function showProductById(
        ProductRepository $productRepository,
        int $bookId
    ): Response {
        $product = $productRepository
        ->find($bookId);

        return $this->json($product);
    }

    #[Route('/product/delete/{id}', name: 'product_delete_by_id')]
    public function deleteProductById(
        ProductRepository $productRepository,
        int $bookId
    ): Response {
        $product = $productRepository->find($bookId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$bookId
            );
        }

        $productRepository->remove($product, true);

        return $this->redirectToRoute('product_show_all');
    }

    #[Route('/product/update/{id}/{value}', name: 'product_update')]
    public function updateProduct(
        ProductRepository $productRepository,
        int $bookId,
        int $value
    ): Response {
        $product = $productRepository->find($bookId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$bookId
            );
        }

        $product->setValue($value);
        $productRepository->save($product, true);

        return $this->redirectToRoute('product_show_all');
    }
}
