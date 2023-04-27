<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Cards\Card;
use App\Cards\Deck;
use App\Cards\CardGraphic;

class CardController extends AbstractController
{
    /**
    * @Route("/card", name="start")
    */
    public function start(): Response
    {
        return $this->render('card.html.twig');
    }

    /**
    * @Route("/card/deck", name="deck")
    */
    public function deck(): Response
    {
        $deck = new Deck();
        $deck->createDeck();
        return $this->render('deck.html.twig', ['deck' => $deck->getDeck()]);
    }
}