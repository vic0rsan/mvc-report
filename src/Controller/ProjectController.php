<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Cards\FiveCardPoker;

class ProjectController extends AbstractController
{
    #[Route("/proj", name: "project_index")]
    public function ProjectIndex()
    {
        return $this->render('project/index.html.twig');
    }

    #[Route("/proj/game", name: "project_game")]
    public function ProjectGame(SessionInterface $session): Response
    {
        //var_dump($session->get("swap"));
        $game = $session->get("game");

        if ($game->getTurn() > 3) {
            $game->setTurn(0);
        }

        if (!$game) {
            $game = new FiveCardPoker();
            $game->getDeck()->createDeck();
            $game->getDeck()->shuffleDeck();
            $game->dealHand();
            $session->set("game", $game);
        }

        return $this->render('project/fivecard.html.twig', [
            "title" => "Five Card Poker",
            "player" => $game->getPlayerHand(),
            "com" => $game->getComHand(),
            "round" => $game->getTurn()
        ]);
    }

    #[Route("/proj/game/swap", name: "swap_card", methods: ['POST'])]
    public function SwapCard(SessionInterface $session, Request $body): Response
    {
        $game = $session->get("game");
        $swap = $body->request->all();
        $session->set("swap", $swap);
        $game->swapCard($swap);
        $game->incTurn();

        return $this->redirectToRoute("project_game");
    }
}