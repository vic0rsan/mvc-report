<?php

namespace App\Cards;

class FiveCardPoker
{
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
        $this->turn = 0;
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

    public function incTurn()
    {
        $this->turn += 1;
    }

    public function swapCard(array $swap)
    {
        $card = $this->deck->draw(sizeof($swap));
        $this->player->addAtIndex($swap, $card);
    }
}