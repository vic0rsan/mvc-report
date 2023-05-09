<?php

namespace App\Cards;

use App\Cards\Card;

class CardHand
{
    /**
     * @var array<Card>
     */
    private array $hand;

    public function __construct()
    {
        $this->hand = [];
    }

    /**
     * @param array<Card> $card
     */
    public function add(array $card): void
    {
        foreach ($card as $item) {
            array_push($this->hand, $item);
        }  
    }

    /**
     * @return array<Card>
     */
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