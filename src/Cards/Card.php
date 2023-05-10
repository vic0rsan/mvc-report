<?php

namespace App\Cards;

/**
 * Card klassen för att skapa kort. 
 * Ett kort-objekt har attribut för dess färg, rang och poäng.
 */
class Card
{
    public string $suite;
    public string $rank;
    public int $point;

    /**
     * Konstruktorn för att skapa ett kort.
     * Metoden tar 3st inparametrar;
     * $suite för färgen, $rank för rangen
     * och $point för poängen som kortet representerar. 
     */
    public function __construct(
        string $suite,
        string $rank,
        int $point
    ) {
        $this->suite = $suite;
        $this->rank = $rank;
        $this->point = $point;
    }

    /**
     * Returnerar kortets tillhörande färg.
     */
    public function getSuite(): string
    {
        return $this->suite;
    }

    /**
     * Returnerar kortets rang.
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * Returnerar kortets värde (i ex Game 21).
     */
    public function getPoint(): int
    {
        return $this->point;
    }
}
