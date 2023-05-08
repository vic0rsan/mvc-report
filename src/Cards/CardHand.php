<?php

namespace App\Cards;

use App\Cards\Card;

class CardHand
{
    /**
     * @var array<Card>
     */
    private array $hand;

    public function __construct(array $hand = [])
    {
        $this->hand = $hand;
    }

    public function add(array $card): void
    {
        for ($i = 0; $i < count($card); $i++) {
            array_push($this->hand, $card[$i]);
        }
    }

    public function getHand(): array
    {
        return $this->hand;
    }

    public function getSum(): int
    {
        $points = 0;
        foreach ($this->hand as $card) {
            $points += $card->point;
        }
        return $points;
    }
}
