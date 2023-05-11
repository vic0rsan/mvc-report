<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Cards\Game21;

class GameController extends AbstractController
{
    #[Route("/game", name: "game_index")]
    public function game21(): Response
    {
        return $this->render('game/index.html.twig', ['title' => "Game Index"]);
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
        $session->set('status', "Ongoing match");

        return $this->redirectToRoute('game_start');
    }

    #[Route("/game/start", name:"game_start")]
    public function initGame(SessionInterface $session): Response
    {
        if (!$session->get('game')) {
            return $this->redirectToRoute('game_init');
        }

        $game = $session->get('game');
        $playerPoints = $game->getPlayerPoint();
        $bankPoints = $game->getBankPoint();
        $gameover = $game->getGameover();
        $message = "";

        if ($gameover) {
            $message = $game->comparePoints();
            $session->set('status', $message);
        }

        $data = [
            'title' => "Game 21",
            'player' => $game->getPlayer()->getHand(),
            'bank' => $game->getBank()->getHand(),
            'gameover' => $gameover,
            'message' => $message,
            'player_points' => $playerPoints,
            'bank_points' => $bankPoints
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

    #[Route("/game/doc", name: "game_doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig', ["title" => "Game Doc"]);
    }

    #[Route("/api/game", name: "game_api")]
    public function api(SessionInterface $session): Response
    {
        $player = null;
        $bank = null;

        if ($session->get('game')) {
            $player = $session->get('game')->getPlayerPoint();
            $bank = $session->get('game')->getBankPoint();
        }

        $status = $session->get('status');
        $data = [
            'player' => $player,
            'bank' => $bank,
            'status' => $status
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
