<?php

namespace App\Cards;

use App\Cards\CompareRank;

/**
 * Klassen för att skapa en omgång av Femkorts poker.
 */
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

    /**
     * Metoden skapar en ny uppsättning av en kortlek och delar sedan ut
     * 5 kort vardera till spelaren och datorn.
     */
    public function dealHand(): void
    {
        $this->deck->createDeck();
        $this->deck->shuffleDeck();

        $card = $this->deck->draw(5);
        $this->player->add($card);

        $card = $this->deck->draw(5);
        $this->com->add($card);
    }

    public function getPlayer(): CardHand
    {
        return $this->player;
    }

    /**
     * @return array<Card>
     */
    public function getPlayerHand(): array
    {
        return $this->player->getHand();
    }

    /**
    * Metoden används enbart vid testande då man vill definera en hand för spelaren.
    * @param array<array{rank:int,suit:string}> $card
    */
    public function setPlayerHand(array $card): void
    {
        $this->player = new CardHand();
        $hand = [];
        $count = count($card);
        for ($i = 0; $i < $count; $i++) {
            array_push($hand, new Card((string)$card[$i]["suit"], "", (int)$card[$i]["rank"]));
        }
        $this->player->add($hand);
    }

    /**
     * @return array<Card>
     */
    public function getComHand(): array
    {
        return $this->com->getHand();
    }

    /**
    * Metoden används enbart vid testande då man vill definera en hand för Datorn.
    * @param array<array{rank:int,suit:string}> $card
    */
    public function setComHand(array $card): void
    {
        $this->com = new CardHand();
        $hand = [];
        $count = count($card);
        for ($i = 0; $i < $count; $i++) {
            array_push($hand, new Card((string)$card[$i]["suit"], "", (int)$card[$i]["rank"]));
        }
        $this->com->add($hand);
    }

    public function getTurn(): int
    {
        return $this->turn;
    }

    public function getPot(): int
    {
        return $this->pot;
    }

    /**
     * En setter metod för att öka den totala spelpotten
     */
    public function incPot(int $pot): void
    {
        $this->pot += $pot;
    }

    /**
     * En setter metod för att öka den nuvarande spel-rundan
     */
    public function incTurn(): void
    {
        $this->turn += 1;
    }

    /**
     * Metoden för att byta kort utvalda kort. Metoden har två parametrar:
     * en $swap som är en array där det innehåller index för kort/korten som skall bytas ut.
     * en $test som innehåller definerade kort-objekt, används enbart vid enhetstester.
     * @param array<int> $swap
     * @param array<Card> $test
     */
    public function swapCard(array $swap = [], array $test = []): void
    {
        if ($test) {
            $this->player->addAtIndex($swap, $test);
            return;
        }
        $card = $this->deck->draw(count($swap));
        $this->player->addAtIndex($swap, $card);
    }

    /**
    * Metoden räknar ut vilken kortrank som en hand har inom femkorts poker.
    * Metoden returnerar ranken som en string.
    * @param array<array{rank:int,suit:string}> $hand
    */
    public function getPokerRank(array $hand): string
    {
        $handCount = array_count_values(array_column($hand, 'rank'));
        $compare = new CompareRank();

        if ($this->getFlush($hand)) {
            return (string)$this->getFlush($hand);
        }

        if ($compare->isStraight($hand)) {
            return "Straight";
        }

        if ($compare->isFourOfAKind($handCount)) {
            return "Four of a Kind";
        }

        if ($compare->isFullHouse($handCount)) {
            return "Full House";
        }

        if ($compare->isThreeOfAKind($handCount)) {
            return "Three of a Kind";
        }

        if ($compare->isTwoPair($handCount)) {
            return "Two Pair";
        }

        if ($compare->isOnePair($handCount)) {
            return "One Pair";
        }

        return "High Card";
    }

    /**
    * Metod för att ränka ut vilken typ av Flush som spelaren har.
    * @param array<array{rank:int,suit:string}> $hand
    */
    private function getFlush(array $hand): string|bool
    {
        $handUnique = count(array_unique(array_column($hand, 'suit'))) == 1;
        $compare = new CompareRank();

        if ($compare->isRoyalFlush($hand, $handUnique)) {
            return "Royal Flush";
        }

        if ($compare->isStraightFlush($compare->isStraight($hand), $handUnique)) {
            return "Straight Flush";
        }

        if ($handUnique) {
            return "Flush";
        }

        return false;
    }

    /**
     * Metoden avgör vem av spelarna som har högst hand om rankerna är samma.
     */
    private function compareRank(): string
    {
        $player = array_column($this->player->getHandRank(), 'rank');
        $com = array_column($this->com->getHandRank(), 'rank');
        $playerCount = array_count_values($player);
        $comCount = array_count_values($com);
        $rank = $this->getPokerRank($this->player->getHandRank());
        $compare = new CompareRank();

        rsort($player);
        rsort($com);

        $winner = "";
        switch ($rank) {
            case "High Card":
            case "Straight":
            case "Straight Flush":
            case "Royal Flush":
            case "Flush":
                $winner = $compare->compareHighCard($player, $com);
                break;
            case "One Pair":
                $winner = $compare->compareOnePair($playerCount, $comCount);
                break;
            case "Two Pair":
                $winner = $compare->compareTwoPair($playerCount, $comCount);
                break;
            case "Full House":
            case "Three of a Kind":
                $winner = $compare->compareThreeOfAKind($playerCount, $comCount);
                break;
            case "Four of a Kind":
                $winner = $compare->compareFourOfAKind($playerCount, $comCount);
                break;
        }
        return $winner;
    }

    /**
     * Metoden ränknar ut värdena för spelarnas händer och avgör vinnaren/förloraren.
     */
    public function compareHand(): string
    {
        $rank = self::POKERRANK[$this->getPokerRank($this->player->getHandRank())];
        if ($rank == self::POKERRANK[$this->getPokerRank($this->com->getHandRank())]) {
            return $this->compareRank();
        } elseif (self::POKERRANK[$this->getPokerRank($this->player->getHandRank())] > self::POKERRANK[self::getPokerRank($this->com->getHandRank())]) {
            return "Spelaren vann";
        }

        return "Datorn vann";
    }

    /**
     * Följande metod ansvarar för datorns innrelogik där bland annat
     * avgörs vilka kort ska bytas ut eller inte samt om datorn vill "raise" eller "call" spelarens pott.
     * @return array<int|null>
     */
    public function comLogic(int $playerPot, int $raise = 0): array
    {
        $hand = $this->com->getHandRank();
        $count = array_count_values(array_column($hand, 'rank'));
        $rank = $this->getPokerRank($hand);

        if ($raise == 0) {
            $raise = rand(1, 2);
        }

        if ($raise == 1) {
            $this->incPot($playerPot + rand(50, 200));
        } elseif ($raise == 2) {
            $this->incPot($playerPot);
        }

        $swap = [];
        switch ($rank) {
            case "Three of a Kind":
                $card = array_search(3, $count);
                $swap = array_keys(array_filter($hand, function ($item) use ($card) {
                    return $item['rank'] !== $card;
                }));
                break;
            case "One Pair":
                $card = array_search(2, $count);
                $swap = array_keys(array_filter($hand, function ($item) use ($card) {
                    return $item['rank'] !== $card;
                }));
                break;
            case "Two Pair":
                $card = array_search(1, $count);
                $swap = array_keys(array_filter($hand, function ($item) use ($card) {
                    return (int)$item['rank'] == (int)$card;
                }));
                break;
            case "High Card":
                $swap = [0, 1, 2, 3, 4];
                break;
            default:
                return $swap;
        }
        $card = $this->deck->draw(count($swap));
        $this->com->addAtIndex($swap, $card);

        return $swap;
    }
}
