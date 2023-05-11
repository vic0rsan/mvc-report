<?php

namespace App\Cards;

use App\Cards\Card;

class CardHand
{
    /**
     * @var array<Card>
     */
    private array $hand;

    /**
     * Konstruktorn för att skapa ett CardHand-objekt.
     * CardHand representerar en korten i hand för ett kortspel.
     */
    public function __construct()
    {
        $this->hand = [];
    }

    /**
     * Metoden lägger till en eller flera kort i handen för spelaren.
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

    /**
     * Metoden summerar kortens värde för en hand.
     * Används tex för 21-spelet.
     */
    public function getSum(): int
    {
        $points = 0;
        foreach ($this->hand as $card) {
            $points += $card->point;
        }
        return $points;
    }
}
