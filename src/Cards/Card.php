<?php

namespace App\Cards;

class Card
{
    public string $suite;
    public string $rank;


    public function __construct(
        string $suite,
        string $rank
    ) {
        $this->suite = $suite;
        $this->rank = $rank;
    }

    public function getSuite(): string
    {
        return $this->$suite;
    }

    public function getRank(): string
    {
        return $this->$rank;
    }
}
