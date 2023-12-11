<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class ApiController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function jsonApi(): Response
    {
        return $this->render('api.html.twig');
    }

    #[Route("/api/quote", name: "quote")]
    public function jsonQuote(): Response
    {
        $quotes = array(
            "Fear is a reaction. Courage is a decision.",
            "You will never reach your destination if you stop and throw stones at every dog that barks.",
            "Success consists of going from failure to failure without loss of enthusiasm."
        );
        $number = random_int(0, 2);
        $genTime = new DateTime();
        $data = [
            'quote of the day' => $quotes[$number],
            'date' => date('Y-m-d'),
            'generated' => $genTime->getTimestamp()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
