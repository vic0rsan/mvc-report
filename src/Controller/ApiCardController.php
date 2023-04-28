<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Cards\Card;
use App\Cards\Deck;
use App\Cards\CardGraphic;

class ApiCardController
{
    #[Route("/api/deck", name: "json_deck", methods: ['GET'])]
    public function jsonDeck(): Response
    {
        $deck = new Deck();
        $deck->createDeck();
        $data = [
            'deck' => $deck->getDeck()
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "json_shuffle", methods: ['POST'])]
    public function jsonShuffle(SessionInterface $session): Response
    {
        $deck = new Deck();
        $deck->createDeck();
        $deck->shuffleDeck();

        $session->set('deck', $deck);

        $data = [
            'deck' => $deck->getDeck()
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}