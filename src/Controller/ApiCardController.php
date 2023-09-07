<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Cards\Card;
use App\Cards\Deck;
use App\Cards\CardGraphic;

class ApiCardController extends AbstractController
{
    #[Route("/api/deck", name: "json_deck", methods: ['GET'])]
    public function jsonDeck(SessionInterface $session): Response
    {
        if (!$session->get('deck')) {
            $deck = new Deck();
            $deck->createDeck();
            $session->set('deck', $deck);
        }

        $deck = $session->get('deck');

        $data = [
            'deck' => $deck->getDeck()
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "json_shuffle", methods: ['POST', 'GET'])]
    public function jsonShuffle(SessionInterface $session): Response
    {
        $deck = new Deck();
        $deck->createDeck();
        $deck->shuffleDeck();

        $session->set('deck', $deck);
        $session->set('pick', []);

        $data = [
            'deck' => $deck->getDeck()
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/draw/{number}", name: "json_draw", methods: ['POST', 'GET'])]
    public function jsonDraw(SessionInterface $session, int $number = 1): Response
    {
        $deck = $session->get("deck");
        $pick = $session->get("pick");
        $newCard = [];

        if ($deck->cardLeft() >= $number) {
            $newCard = $deck->draw(abs($number));
        }

        $newPick = array_merge($pick, $newCard);
        $session->set("deck", $deck);
        $session->set("pick", $newPick);

        $data = [ 'deck' => $newPick, 'remain' => $deck->cardLeft() ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }
}
