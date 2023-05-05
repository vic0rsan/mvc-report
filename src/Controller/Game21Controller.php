<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Cards\Card;
use App\Cards\Deck;
use App\Cards\CardGraphic;

class Game21Controller extends AbstractController
{
    #[Route("/game", name: "game")]
    public function game21(SessionInterface $session): Response
    {
        return $this->render('card/game21.html.twig');
    }

    #[Route("/game/start", name:"game-start")]
    public function initGame(SessionInterface $session): void
    {

    }
}