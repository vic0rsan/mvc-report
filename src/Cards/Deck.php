<?php

namespace App\Cards;

use App\Cards\CardGraphic;

class Deck
{
    /**
     * @var array<Card>
     */
    private array $deck;
    private const SUITS = ["heart", "spade", "club", "diamond"];
    private const RANKS = [
        "2" => 2,
        "3" => 3,
        "4" => 4,
        "5" => 5,
        "6" => 6,
        "7" => 7,
        "8" => 8,
        "9" => 9,
        "10" => 10,
        "jack" => 11,
        "queen" => 12,
        "king" => 13,
        "ace" => 14,
    ];

    public function __construct()
    {
        $this->deck = [];
    }

    public function createDeck(): void
    {
        $this->deck = [];
        foreach (self::SUITS as $suit) {
            foreach (self::RANKS as $name => $point) {
                $card = new CardGraphic($suit, strval($name), $point);
                array_push($this->deck, $card);
            }
        }
    }

    /**
     * @return array<Card>
     */
    public function draw(int $number = 1): array
    {
        $pick = [];

        for ($i = 0; $i < $number; $i++) {
            $index = rand(0, count($this->deck) - 1);
            array_push($pick, $this->deck[$index]);
            array_splice($this->deck, $index, 1);
        }

        return $pick;
    }

    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    /**
     * @return array<Card>
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    public function cardLeft(): int
    {
        return count($this->deck);
    }
}
