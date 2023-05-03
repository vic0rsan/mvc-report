<?php

namespace App\Cards;

use App\Cards\Card;

class CardHand
{
    /**
     * @var array<array>
     * @phpstan-ignore-next-line
     */
    private array $hand;

    public function __construct()
    {
        $this->hand = [];
    }

    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    public function draw(): void
    {
        for ($i = 0; $i < count($this->hand); $i++) {
            ;;
        }
    }
}
