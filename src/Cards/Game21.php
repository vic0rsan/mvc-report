<?php

namespace App\Cards;

class Game21
{
    protected CardHand $player;
    protected CardHand $bank;
    protected Deck $deck;
    protected bool $gameover;

    /**
     * Konstruktorn för att skapa ett Game21-instans.
     */
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

    /**
     * Metoden returnerar poängen för spelarens ställning.
     * Poängen tars fram genom CardHand-klassens getSum metod.
     */
    public function getPlayerPoint(): int
    {
        return $this->player->getSum();
    }

    /**
     * Metoden returnerar poängen för Datorns/Bankens ställning.
     */
    public function getBankPoint(): int
    {
        return $this->bank->getSum();
    }

    /**
     * Metoden returnerar true/false beroende om spelet är över eller ej.
     */
    public function getGameover(): bool
    {
        return $this->gameover;
    }

    public function getDeck(): Deck
    {
        return $this->deck;
    }

    /**
     * Metoden för att markera att spelet är över.
     */
    public function setGameover(): void
    {
        $this->gameover = true;
    }

    /**
     * Metod för att dela ett kort till spelaren
     */
    public function playerDraw(): void
    {
        $card = $this->deck->draw();
        $this->player->add($card);
    }

    /**
     * Metod för att dela ett kort till datorn/banken
     */
    public function bankDraw(): void
    {
        $card = $this->deck->draw();
        $this->bank->add($card);
    }

    /**
     * Metod som representerar datorns/bankens logik.
     * Datorn tar kort tills den har nått en summa >= 17
     */
    public function bankTurn(): void
    {
        $pointSum = $this->bank->getSum();
        while ($pointSum < 17) {
            $this->bankDraw();
            $pointSum = $this->bank->getSum();
        }
    }

    /**
     * Metod för att beräkna vem som vinner.
     * Poängen jämförs och returneras en string om vem son vann.
     */
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
