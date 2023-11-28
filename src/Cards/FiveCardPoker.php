<?php

namespace App\Cards;

class FiveCardPoker
{
    private const POKERRANK = [
        'High Card' => 1,
        'One Pair' => 2,
        'Two Pair' => 3,
        'Three of a Kind' => 4,
        'Straight' => 5,
        'Flush' => 6,
        'Full House' => 7,
        'Four of a Kind' => 8,
        'Straight Flush' => 9,
        'Royal Flush' => 10,
    ];

    private const MAXPOT = 1000;

    protected CardHand $player;
    protected CardHand $com;
    protected Deck $deck;
    protected int $turn;
    protected int $pot;

    public function __construct()
    {
        $this->player = new CardHand();
        $this->com = new CardHand();
        $this->deck = new Deck();
        $this->turn = 1;
        $this->pot = 0;
    }

    public function dealHand(): void
    {
        $this->turn = 1;

        $card = $this->deck->draw(5);
        $this->player->add($card);

        $card = $this->deck->draw(5);
        $this->com->add($card);
    }

    /**
     * @return array<Card>
     */
    public function getPlayerHand(): array
    {
        return $this->player->getHand();
    }

    /**
     * @return array<Card>
     */
    public function getComHand(): array
    {
        return $this->com->getHand();
    }

    public function getPlayer(): CardHand
    {
        return $this->player;
    }

    public function getCom(): CardHand
    {
        return $this->com;
    }

    public function getDeck(): Deck
    {
        return $this->deck;
    }

    public function getTurn(): int
    {
        return $this->turn;
    }

    public function getPot(): int
    {
        return $this->pot;
    }

    public function setTurn(int $turn): void
    {
        $this->turn = $turn;
    }

    public function incPot(int $pot): void
    {
        $this->pot += $pot;
    }

    public function incTurn(): void
    {
        $this->turn += 1;
    }

    /**
     * @param array<int> $swap
     */
    public function swapCard(array $swap): void
    {
        $card = $this->deck->draw(count($swap));

        $this->com->addAtIndex($swap, $card);
        $this->player->addAtIndex($swap, $card);
    }

    /**
     * @param array<int<0,max>,array<int|string>> $hand
     */
    private function isStraight(array $hand): bool
    {
        usort($hand, function ($current, $next) {
            return (int)$current['rank'] - (int)$next['rank'];
        });

        $max = count($hand);
        for ($i = 0; $i < $max - 1; $i++) {
            if ((int)$hand[$i + 1]['rank'] -  (int)$hand[$i]['rank'] != 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array<int<0,max>,array<int|string>> $hand
     */
    public function getPokerRank(array $hand): string
    {
        $handCount = array_count_values(array_column($hand, 'rank'));
        $handUnique = count(array_unique(array_column($hand, 'suit'))) == 1;

        $isStraight = self::isStraight($hand);

        if (array_sum(array_column($hand, 'rank')) == 60 && $handUnique) {
            return "Royal Flush";
        }

        if ($isStraight && $handUnique) {
            return "Straight Flush";
        }

        if ($handUnique) {
            return "Flush";
        }

        if ($isStraight) {
            return "Straight";
        }

        if (in_array(4, $handCount)) {
            return "Four of a Kind";
        }

        if (in_array(3, $handCount) && in_array(2, $handCount)) {
            return "Full House";
        }

        if (in_array(3, $handCount)) {
            return "Three of a Kind";
        }

        if (count(array_keys($handCount, 2)) == 2) {
            return "Two Pair";
        }

        if (in_array(2, $handCount)) {
            return "One Pair";
        }

        return "High Card";
    }

    public function compareHand(): string
    {
        if (self::POKERRANK[self::getPokerRank($this->player->getHandRank())] == self::POKERRANK[self::getPokerRank($this->com->getHandRank())]) {
            if ($this->player->getSum() == $this->com->getSum()) {
                return "Oavgjort";
            } elseif ($this->player->getSum() > $this->com->getSum()) {
                return "Spelaren vann";
            }

            return "Datorn vann";
        } elseif (self::POKERRANK[self::getPokerRank($this->player->getHandRank())] > self::POKERRANK[self::getPokerRank($this->com->getHandRank())]) {
            return "Spelaren vann";
        }

        return "Datorn vann";
    }

    /**
     * @return array<int>
     */
    public function comLogic(): array
    {
        $pot = [
            'High Card' => 50,
            'One Pair' => 100,
            'Two Pair' => 200,
            'Three of a Kind' => 300,
            'Straight' => 500,
            'Flush' => 500,
            'Full House' => 600,
            'Four of a Kind' => 700,
            'Straight Flush' => 800,
            'Royal Flush' => 900,
        ];

        $hand = $this->com->getHandRank();
        $count = array_count_values(array_column($hand, 'rank'));
        $rank = self::getPokerRank($hand);

        self::incPot(rand($pot[$rank], self::MAXPOT));

        switch ($rank) {
            case $rank == "Three of a Kind":
                $card = array_search(3, $count);
                return array_keys(array_filter($hand, function ($item) use ($card) {
                    return $item !== $card;
                }));
            case $rank == "One Pair":
                $card = array_search(2, $count);
                return array_keys(array_filter($hand, function ($item) use ($card) {
                    return $item !== $card;
                }));
            case $rank == "Two Pair":
                $card = array_search(1, $count);
                return array_keys(array_filter($hand, function ($item) use ($card) {
                    return (int)$item == (int)$card;
                }));
            case $rank == "High Card":
                return [0, 1, 2, 3, 4];
        }

        return [];
    }
}
