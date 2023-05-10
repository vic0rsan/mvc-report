<?php

namespace App\Cards;

use App\Cards\CardGraphic;

/**
 * Klassen för att skapa ett kortspel av 52st kort.
 */
class Deck
{
    /**
     * Arayerna innehåller alla attribut för färg, 
     * rang och poäng ett kort kan anta. 
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

    /**
     * Konstrukturn för att skapa ett kortdeck.
     * Deck instansattribut initierar med en tom array.
     */
    public function __construct()
    {
        $this->deck = [];
    }

    /**
     * Metod för att skapa samtliga 52 kort i kortleken.
     * Den ny skapta kortleken lagra i instansattributet $deck
     */
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
     * Metoden för att dra ett eller flera kort från kortleken.
     * Kortet väljs slumpmäsigt och pushas till den nya array $pick.
     * Metoden returnerar array $pick, dvs de valda korten.
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

    /**
     * Metoden för att blanda kortleken.
     */
    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    /**
     * Metoden returnerar kortleken.
     * @return array<Card>
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * Metoden returnerar antalet kort kvar i kortleken.
     */
    public function cardLeft(): int
    {
        return count($this->deck);
    }
}
