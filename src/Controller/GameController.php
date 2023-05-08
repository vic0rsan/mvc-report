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

    #[Route("/game/init", name: "game_init", methods: ["POST"])]
    public function gameInit(SessionInterface $session): Response
    {
        $deck = new Deck();
        $deck->createDeck();
        $deck->shuffleDeck();
        $session->set('deck', $deck);

        $session->set('player', []);
        $session->set('dealer', []);

        return $this->redirectToRoute('game_start');
    }

    #[Route("/game/start", name:"game_start")]
    public function initGame(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        
        if (!$session->get('player') || !$session->get('dealer')) {
            $player_card = $deck->draw();
            $dealer_card = $deck->draw();
            $session->set('player', new CardHand($player_card));
            $session->set('dealer', new CardHand($dealer_card));
            $session->set('player_points', $player_card[0]->point);
            $session->set('dealer_points', $dealer_card[0]->point);
        }

        $player = $session->get('player');
        $dealer = $session->get('dealer');
        $player_points = $session->get('player_points');
        $dealer_points = $session->get('dealer_points');

        $data = [
            'title' => "Game 21",
            'player' => $player->getHand(),
            'delaer' => $dealer->getHand(),
            'player_points' => $player_points,
            'dealer_points' => $dealer_points
        ];
        
        return $this->render('game/game.html.twig', $data);
    }

    #[Route("/game/draw", name: "game_draw", methods: ['POST'])]
    public function draw(SessionInterface $session): Response
    {
        $player = $session->get('player');
        $point = $session->get('player_points');
        $deck = $session->get('deck');
        
        $draw = $deck->draw();
        $player->add($draw);

        $session->set('player_points', $point + $draw[0]->point);
        $session->set('player', $player);
        $session->set('deck', $deck);

        return $this->redirectToRoute('game_start');
    }
}