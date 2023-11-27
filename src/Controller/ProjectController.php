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
        $game = $session->get("game");
        $status = null;

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
            "round" => $game->getTurn(),
            "bet" => $session->get("bet"),
            "pot" => $game->getPot(),
            "gameover" => $session->get("gameover"),
            "status" => $session->get("status")
        ]);
    }

    #[Route("/proj/game/swap", name: "swap_card", methods: ['POST'])]
    public function SwapCard(SessionInterface $session, Request $body): Response
    {
        $game = $session->get("game");
        $swap = $body->request->all();

        $game->swapCard($swap, false);
        $game->SwapCard($game->comLogic(), true);
        $session->set("bet", true);

        return $this->redirectToRoute("project_game");
    }

    #[Route("/proj/game/pot", name: "add_pot", methods: ['POST'])]
    public function AddPot(SessionInterface $session, Request $body): Response
    {
        $game = $session->get("game");
        $pot = $body->request->get('pot');
        $game->incPot($pot);
        $session->set("bet", false);
        $game->incTurn();

        if ($game->getTurn() == 4) {
            $session->set("status", $game->compareHand() . " (" . $game->getPokerRank($game->getPlayer()->getHandRank()). ")");
            $session->set("gameover", true);
        }

        return $this->redirectToRoute("project_game");
    }

    #[Route("/proj/game/reset", name: "game_reset", methods: ['POST'])]
    public function GameReset(SessionInterface $session): Response
    {
        $session->set("gameover", false);
        $session->set("game", null);
        $session->set("status", null);

        return $this->redirectToRoute("project_game");
    }

    #[Route("/proj/about", name: "project_about")]
    public function ProjectAbout()
    {
        return $this->render('project/about.html.twig');
    }
}