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
    protected int $playerPot;
    protected int $comPot;

    public function __construct()
    {
        $this->player = new CardHand();
        $this->com = new CardHand();
        $this->deck = new Deck();
        $this->turn = 1;
        $this->playerPot = 0;
        $this->comPot = 0;
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

    public function getPlayerPot()
    {
        return $this->playerPot;
    }

    public function setTurn(int $turn)
    {
        $this->turn = $turn;
    }

    public function incPlayerPot(int $pot)
    {
        $this->playerPot += $pot;
    }

    public function incTurn()
    {
        $this->turn += 1;
    }

    public function swapCard(array $swap)
    {
        $card = $this->deck->draw(sizeof($swap));
        $this->player->addAtIndex($swap, $card);
    }

    private function nextCard($card) {
        return substr($card, -1);
    }

    public function getPokerRank(array $hand)
    {
        $handCount = array_count_values($hand);

        if (in_array(4, $handCount)) {
            return "Four of a Kind";
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

        if (max($hand) - min($hand) == 4 && count($handCount) == 5) {
            return "Straight";
        }

        if (count(array_unique(array_map('nextCard', $hand))) == 1) {
            return "Flush";
        }

        if (max($hand) == 14 && min($hand) == 10 && count($handCount) == 5) {
            return "Royal Flush";
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
}