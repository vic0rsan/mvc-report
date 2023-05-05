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
        array_push($this->hand, $card);
    }

    public function getHand(): array
    {
        return $this->hand;
    }
}
