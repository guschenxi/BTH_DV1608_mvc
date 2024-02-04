<?php

namespace App\Controller;

use App\Dice\Dice;
use App\Dice\DiceGraphic;
use App\Dice\DiceHand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportControllerTwig extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/lucky/", name: "lucky")]
    public function lucky(): Response
    {
        $number = random_int(0, 1000);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky.html.twig', $data);
    }


    #[Route("/api/quote")]
    public function jsonNumber(): Response
    {
        $quotes = array(
            'Gratitude makes sense of our past, brings peace for today, and creates a vision for tomorrow.',
            'Today you are you!',
            'Each day of your life, as soon as you open your eyes in the morning, you can square away for a happy and successful day.',
            'Never give up!'
        );
        $random_index = random_int(0, count($quotes) - 1);

        $data = [
            'date' => date("Y-m-d"),
            'timestamp' => time(),
            'quote' => $quotes[$random_index],
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/game/pig/test/roll", name: "test_roll_dice")]
    public function testRollDice(): Response
    {
        $die = new Dice();

        $data = [
            "dice" => $die->roll(),
            "diceString" => $die->getAsString(),
        ];

        return $this->render('pig/test/roll.html.twig', $data);
    }
}
