<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Cards\Card;
use App\Cards\Deck;
use App\Cards\CardGraphic;

class CardController extends AbstractController
{
    /**
    * @Route("/card", name="card")
    */
    public function start(): Response
    {
        return $this->render('card/card.html.twig');
    }

    /**
    * @Route("/card/deck", name="deck")
    */
    public function deck(SessionInterface $session): Response
    {
        if (!$session->get('deck')) {
            $deck = new Deck();
            $deck->createDeck();
            $session->set('deck', $deck);
        }

        $deck = $session->get('deck');

        $data = [
            'title' => "Deck",
            'deck' => $deck->getDeck()
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    /**
    * @Route("/card/deck/shuffle", name="shuffle")
    */
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new Deck();
        $deck->createDeck();
        $deck->shuffleDeck();

        $session->set('deck', $deck);

        if ($session->get('pick')) {
            $session->set('pick', []);
        }

        $data = [
            'title' => "Shuffled deck",
            'deck' => $deck->getDeck()
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/api/deck/draw/{number}", name: "draw", methods: ['POST', 'GET'])]
    public function draw(SessionInterface $session, int $number = 1): Response
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

        $data = [ 'deck' => $newPick, 'remain' => $deck->cardLeft(), 'title' => "Drawed cards" ];

        return $this->render('card/draw.html.twig', $data);
    }
}