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

    public function dealHand()
    {
        $this->turn = 1;

        $card = $this->deck->draw(5);
        $this->player->add($card);

        $card = $this->deck->draw(5);
        $this->com->add($card);
    }

    public function getPlayerHand()
    {
        return $this->player->getHand();
    }

    public function getComHand()
    {
        return $this->com->getHand();
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function getCom()
    {
        return $this->com;
    }

    public function getDeck()
    {
        return $this->deck;
    }

    public function getTurn()
    {
        return $this->turn;
    }

    public function getPot()
    {
        return $this->pot;
    }

    public function setTurn(int $turn)
    {
        $this->turn = $turn;
    }

    public function incPot(int $pot)
    {
        $this->pot += $pot;
    }

    public function incTurn()
    {
        $this->turn += 1;
    }

    public function swapCard(array $swap, bool $com)
    {
        $card = $this->deck->draw(sizeof($swap));

        if ($com) {
            $this->com->addAtIndex($swap, $card);
        } else {
            $this->player->addAtIndex($swap, $card);
        }
    }

    private function isStraight(array $hand)
    {
        usort($hand, function($a, $b) {
            return $a['rank'] - $b['rank'];
        });
        
        for ($i = 0; $i < count($hand) - 1; $i++) {
            if ($hand[$i + 1]['rank'] -  $hand[$i]['rank'] != 1) {
                return false;
            }
        }

        return true;
    }
    
    public function getPokerRank(array $hand)
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

    public function compareHand() {
        if (self::POKERRANK[self::getPokerRank($this->player->getHandRank())] == self::POKERRANK[self::getPokerRank($this->com->getHandRank())]) {
            if ($this->player->getSum() == $this->com->getSum()) {
                return "Oavgjort";
            } else if ($this->player->getSum() > $this->com->getSum()) {
                return "Spelaren vann";
            }

            return "Datorn vann";
        } else if (self::POKERRANK[self::getPokerRank($this->player->getHandRank())] > self::POKERRANK[self::getPokerRank($this->com->getHandRank())]) {
            return "Spelaren vann";
        }

        return "Datorn vann";
    }

    public function comLogic()
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
                    return $item == $card;
                }));
            case $rank == "High Card":
                return [0, 1, 2, 3, 4];
        }

        return [];
    }
}