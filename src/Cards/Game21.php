<?php

namespace App\Cards;

class Game21
{
    protected CardHand $player;
    protected CardHand $bank;
    protected Deck $deck;
    protected bool $gameover;

    public function __construct()
    {
        $this->player = new CardHand();
        $this->bank = new CardHand();
        $this->deck = new Deck();
        $this->gameover = false;
    }

    public function getPlayer(): CardHand
    {
        return $this->player;
    }

    public function getBank(): CardHand
    {
        return $this->bank;
    }

    public function getPlayerPoint(): int
    {
        return $this->player->getSum();
    }

    public function getBankPoint(): int
    {
        return $this->bank->getSum();
    }

    public function getGameover(): bool
    {
        return $this->gameover;
    }

    public function getDeck(): Deck
    {
        return $this->deck;
    }

    public function setGameover(): void
    {
        $this->gameover = true;
    }

    public function playerDraw(): void
    {
        $card = $this->deck->draw();
        $this->player->add($card);
    }

    public function bankDraw(): void
    {
        $card = $this->deck->draw();
        $this->bank->add($card);
    }

    public function bankTurn(): void
    {
        $pointSum = $this->bank->getSum();
        while ($pointSum < 17) {
            $this->bankDraw();
            $pointSum = $this->bank->getSum();
        }
    }

    public function comparePoints(): string
    {
        if ($this->player->getSum() > 21) {
            return "Banken vann!";
        } elseif ($this->bank->getSum() > 21) {
            return "Spelaren vann!";
        } elseif ($this->player->getSum() > $this->bank->getSum()) {
            return "Spelaren vann!";
        } elseif ($this->player->getSum() < $this->bank->getSum()) {
            return "Banken vann!";
        }

        return "Oavgjort!";
    }
}
