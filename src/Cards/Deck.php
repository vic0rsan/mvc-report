<?php

namespace App\Cards;
use App\Cards\Card;

class Deck
{
    private array $deck;
    private const suits = ["hearts", "spades", "clubs", "diamonds"];
    private const ranks = ["2", "3", "4", "5", "6", "7", "8", "9", "10", "jack", "queen", "king", "ace"];

    public function __construct()
    {
        $this->deck = [];
    }

    public function createDeck(): void
    {
        $this->deck = [];
        for ($i = 0; $i < count(self::suits); $i++)
        {
            for ($j = 0; $j < count(self::ranks); $j++)
            {
                $card = new Card(self::suits[$i], self::ranks[$j]);
                array_push($this->deck, $card);
            }
        }
    }

    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    public function getDeck(): array
    {
        return $this->deck;
    }
}