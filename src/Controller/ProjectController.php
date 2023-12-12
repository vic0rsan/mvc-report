<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Cards\FiveCardPoker;

/**
 * Controller klassen för FiveCardPoker projektet med samtliga undersidor.
 */

class ProjectController extends AbstractController
{
    /**
     * Routen för landingssidan för projektet
     */
    #[Route("/proj", name: "project_index")]
    public function projectIndex(): Response
    {
        return $this->render('project/index.html.twig');
    }

    /**
     * Routen för självaste spelet.
     */
    #[Route("/proj/game", name: "project_game")]
    public function projectGame(SessionInterface $session): Response
    {
        $game = $session->get("game");

        if (!$game) {
            $game = new FiveCardPoker();
            $game->dealHand();
            $session->set("game", $game);
            $session->set("minPot", 0);
            $session->set("bet", true);
        }

        return $this->render('project/fivecard.html.twig', [
            "title" => "Five Card Poker",
            "player" => $game->getPlayerHand(),
            "com" => $game->getComHand(),
            "round" => $game->getTurn(),
            "bet" => $session->get("bet"),
            "pot" => $game->getPot(),
            "minPot" => $session->get("minPot"),
            "gameover" => $session->get("gameover"),
            "status" => $session->get("status")
        ]);
    }

    /**
     * Routen för knappen "Byt" som byter det utvalda kortens markerade checkbox.
     */
    #[Route("/proj/game/swap", name: "swap_card", methods: ['POST'])]
    public function swapCard(SessionInterface $session, Request $body): Response
    {
        $game = $session->get("game");
        $swap = $body->request->all();

        $game->swapCard($swap);
        $session->set("bet", true);

        $game->incTurn();

        if ($game->getTurn() == 4) {
            $session->set("status", $game->compareHand());
            $session->set("gameover", true);
        }

        return $this->redirectToRoute("project_game");
    }

    /**
     * Routen för att sätta in sin pott inför varje runda.
     */
    #[Route("/proj/game/pot", name: "add_pot", methods: ['POST'])]
    public function addPot(SessionInterface $session, Request $body): Response
    {
        $game = $session->get("game");
        $pot = $body->request->get('pot');
        $game->incPot($pot);
        $game->comLogic($pot);
        $session->set("minPot", $game->getPot() - $pot);
        $session->set("bet", false);

        return $this->redirectToRoute("project_game");
    }

    /**
     * Routen som nollställer spelet efter en avslutad omgång.
     * Samtliga värden som påverkar det grafiska elementen på sidan nollställs.
     */
    #[Route("/proj/game/reset", name: "game_reset", methods: ['POST'])]
    public function gameReset(SessionInterface $session): Response
    {
        $session->set("gameover", false);
        $session->set("game", null);
        $session->set("status", null);

        return $this->redirectToRoute("project_game");
    }

    /**
     * Routen för projektets om-sida.
     */
    #[Route("/proj/about", name: "project_about")]
    public function projectAbout(): Response
    {
        return $this->render('project/about.html.twig');
    }
}
