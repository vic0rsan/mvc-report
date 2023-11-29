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
        $this->player->addAtIndex($swap, $card);
    }

    /**
     * @param array<int<0,max>,array<int|string>> $hand
     */
    public function getPokerRank(array $hand): string
    {
        $handCount = array_count_values(array_column($hand, 'rank'));

        if ($this->getFlush($hand)) {
            return (string)$this->getFlush($hand);
        }

        if (isStraight($hand)) {
            return "Straight";
        }

        if (isFourOfAKind($handCount)) {
            return "Four of a Kind";
        }

        if (isFullHouse($handCount)) {
            return "Full House";
        }

        if (isThreeOfAKind($handCount)) {
            return "Three of a Kind";
        }

        if (isTwoPair($handCount)) {
            return "Two Pair";
        }

        if (isOnePair($handCount)) {
            return "One Pair";
        }

        return "High Card";
    }

    /**
     * @param array<int<0,max>,array<int|string>> $hand
     */
    private function getFlush(array $hand): string|bool
    {
        $handUnique = count(array_unique(array_column($hand, 'suit'))) == 1;

        if (isRoyalFlush($hand, $handUnique)) {
            return "Royal Flush";
        }

        if (isStraightFlush(isStraight($hand), $handUnique)) {
            return "Straight Flush";
        }

        if ($handUnique) {
            return "Flush";
        }

        return false;
    }

    private function compareRank(): string
    {
        $player = array_column($this->player->getHandRank(), 'rank');
        $com = array_column($this->com->getHandRank(), 'rank');
        $playerCount = array_count_values($player);
        $comCount = array_count_values($com);
        $rank = self::getPokerRank($this->player->getHandRank());

        asort($player);
        asort($com);

        switch ($rank) {
            case "High Card":
            case "Straight":
            case "Flush":
                return compareHighCard($player, $com);
            case "One Pair":
                return compareOnePair($playerCount, $comCount);
            case "Two Pair":
                return compareTwoPair($playerCount, $comCount);
            case "Three of a Kind":
                return compareThreeOfAKind($playerCount, $comCount);
            case "Full House":
                return compareFullHouse($playerCount, $comCount);
            case "Four of a Kind":
                return compareFourOfAKind($playerCount, $comCount);
        }

        return "Oavgjort";
    }

    public function compareHand(): string
    {
        $rank = self::POKERRANK[self::getPokerRank($this->player->getHandRank())];
        if ($rank == self::POKERRANK[self::getPokerRank($this->com->getHandRank())]) {
            return $this->compareRank();
        } elseif (self::POKERRANK[self::getPokerRank($this->player->getHandRank())] > self::POKERRANK[self::getPokerRank($this->com->getHandRank())]) {
            return "Spelaren vann";
        }

        return "Datorn vann";
    }

    public function comLogic(): void
    {
        $pot = [
            'High Card' => 50,
            'One Pair' => 100,
            'Two Pair' => 150,
            'Three of a Kind' => 200,
            'Straight' => 250,
            'Flush' => 300,
            'Full House' => 350,
            'Four of a Kind' => 400,
            'Straight Flush' => 450,
            'Royal Flush' => 500,
        ];

        $hand = $this->com->getHandRank();
        $count = array_count_values(array_column($hand, 'rank'));
        $rank = self::getPokerRank($hand);

        self::incPot(rand($pot[$rank], 2 * $pot[$rank]));

        $swap = [];
        switch ($rank) {
            case $rank == "Three of a Kind":
                $card = array_search(3, $count);
                $swap = array_keys(array_filter($hand, function ($item) use ($card) {
                    return $item !== $card;
                }));
            case $rank == "One Pair":
                $card = array_search(2, $count);
                $swap = array_keys(array_filter($hand, function ($item) use ($card) {
                    return $item !== $card;
                }));
            case $rank == "Two Pair":
                $card = array_search(1, $count);
                $swap = array_keys(array_filter($hand, function ($item) use ($card) {
                    return (int)$item == (int)$card;
                }));
            case $rank == "High Card":
                $swap = [0, 1, 2, 3, 4];
        }
        $card = $this->deck->draw(count($swap));
        $this->com->addAtIndex($swap, $card);
    }
}
