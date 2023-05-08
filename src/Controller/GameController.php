<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Cards\Game21;

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
        $game = new Game21();
        $game->getDeck()->createDeck();
        $game->getDeck()->shuffleDeck();
        $game->playerDraw();
        $game->bankDraw();
        $session->set('game', $game);

        return $this->redirectToRoute('game_start');
    }

    #[Route("/game/start", name:"game_start")]
    public function initGame(SessionInterface $session): Response
    {
        if (!$session->get('game')) {
            return $this->redirectToRoute('game_init');
        }

        $game = $session->get('game');
        $player_points = $game->getPlayerPoint();
        $bank_points = $game->getBankPoint();
        $gameover = $game->getGameover();
        $message = "";

        if ($gameover) {
            $message = $game->comparePoints();
        }

        $data = [
            'title' => "Game 21",
            'player' => $game->getPlayer()->getHand(),
            'bank' => $game->getBank()->getHand(),
            'gameover' => $gameover,
            'message' => $message,
            'player_points' => $player_points,
            'bank_points' => $bank_points
        ];
        
        return $this->render('game/game.html.twig', $data);
    }

    #[Route("/game/draw", name: "game_draw", methods: ['POST'])]
    public function draw(SessionInterface $session): Response
    {
        $game = $session->get('game');
        $game->playerDraw();
        $session->set('game', $game);
        if ($game->getPlayerPoint() >= 21) {
            $game->setGameover();
            return $this->redirectToRoute('game_start');
        }

        return $this->redirectToRoute('game_start');
    }

    #[Route("/game/stop", name: "game_stop", methods: ['POST'])]
    public function stop(SessionInterface $session): Response
    {
        $game = $session->get('game');
        $game->bankTurn();
        $game->setGameover();
        return $this->redirectToRoute('game_start');
    }
}