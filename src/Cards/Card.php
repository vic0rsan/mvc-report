<?php

namespace App\Cards;

class Card
{
    public string $suite;
    public string $rank;
    public int $point;

    public function __construct(
        string $suite,
        string $rank,
        int $point
    ) {
        $this->suite = $suite;
        $this->rank = $rank;
        $this->point = $point;
    }

    public function getSuite(): string
    {
        return $this->suite;
    }

    public function getRank(): string
    {
        return $this->rank;
    }

    public function getPoint(): int
    {
        return $this->point;
    }
}
