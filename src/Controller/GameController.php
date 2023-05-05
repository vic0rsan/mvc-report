<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Cards\Card;
use App\Cards\Deck;
use App\Cards\CardGraphic;
use App\Cards\CardHand;

class GameController extends AbstractController
{
    #[Route("/game", name: "game_index")]
    public function game21(SessionInterface $session): Response
    {
        return $this->render('game/index.html.twig', $data = ['title' => "Game Index"]);
    }

    #[Route("/game/start", name:"game_start")]
    public function initGame(SessionInterface $session): Response
    {
        $deck = new Deck();
        $deck->createDeck();

        $player = new CardHand($deck->draw());
        $dealer = new CardHand($deck->draw());

        $session->set('player', $player);
        $session->set('dealer', $dealer);
        
        return $this->render('game/game.html.twig', $data = ['title' => "Game 21"]);
    }
}