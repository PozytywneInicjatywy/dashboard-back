<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LuckyController extends Controller
{
    public function fetch()
    {
        // Seed random so each day has same lucky numbers.
        srand((int) date('dmY'));

        // Generate numbers as long as they are unique.
        do {
            $numbers = [
                rand(1, 30),
                rand(1, 30)
            ];
        } while ($numbers[0] == $numbers[1]);

        return $this->json([
            "numbers" => $numbers
        ]);
    }
}