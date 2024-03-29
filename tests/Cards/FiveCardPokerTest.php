<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class FiveCardPoker.
*/

class FiveCardPokerTest extends TestCase
{
    public function testDealHand(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();

        $this->assertCount(5, $poker->getPlayerHand());
        $this->assertCount(5, $poker->getComHand());
    }

    public function testGetTurn(): void
    {
        $poker = new FiveCardPoker();

        $this->assertIsInt($poker->getTurn());
        $this->assertEquals(1, $poker->getTurn());
    }

    public function testGetPot(): void
    {
        $poker = new FiveCardPoker();

        $this->assertIsInt($poker->getPot());
        $this->assertEquals(0, $poker->getPot());
    }

    public function testIncPot(): void
    {
        $poker = new FiveCardPoker();
        $poker->incPot(500);
        $poker->incPot(200);

        $this->assertIsInt($poker->getPot());
        $this->assertEquals(700, $poker->getPot());
    }

    public function testIncTurn(): void
    {
        $poker = new FiveCardPoker();
        $poker->incTurn();
        $this->assertEquals(2, $poker->getTurn());
        $poker->incTurn();
        $this->assertEquals(3, $poker->getTurn());
    }

    public function testSwapCard(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();

        $swap = [
            ["rank" => 6, "suit" => "heart"],
            ["rank" => 6, "suit" => "spade"],
            ["rank" => 6, "suit" => "club"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 13, "suit" => "heart"],

        ];
        $poker->setPlayerHand($swap);
        $this->assertEquals($swap, $poker->getPlayer()->getHandRank());

        $poker->swapCard([4], [new Card("heart", "ace", 14)]);

        $this->assertNotEquals($swap[4], $poker->getPlayer()->getHandRank()[4]);
    }

    public function testSwapCardAll(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();

        $hand = $poker->getPlayerHand();
        $poker->swapCard([0,1,2,3,4]);

        $this->assertNotEquals($hand, $poker->getPlayerHand());
    }

    public function testHighCard(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 8, "suit" => "spade"],
            ["rank" => 2, "suit" => "club"],
            ["rank" => 5, "suit" => "diamond"],
            ["rank" => 13, "suit" => "club"],

        ];
        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("High Card", $rank);
    }

    public function testOnePair(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 9, "suit" => "spade"],
            ["rank" => 10, "suit" => "club"],
            ["rank" => 12, "suit" => "heart"],
            ["rank" => 13, "suit" => "club"],

        ];
        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("One Pair", $rank);
    }

    public function testTwoPair(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 5, "suit" => "heart"],
            ["rank" => 5, "suit" => "spade"],
            ["rank" => 10, "suit" => "club"],
            ["rank" => 10, "suit" => "heart"],
            ["rank" => 3, "suit" => "club"],

        ];
        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("Two Pair", $rank);
    }

    public function testThreeOfAKind(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 7, "suit" => "heart"],
            ["rank" => 7, "suit" => "spade"],
            ["rank" => 7, "suit" => "club"],
            ["rank" => 10, "suit" => "heart"],
            ["rank" => 3, "suit" => "club"],

        ];
        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("Three of a Kind", $rank);
    }

    public function testFullHouse(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 7, "suit" => "heart"],
            ["rank" => 7, "suit" => "spade"],
            ["rank" => 7, "suit" => "club"],
            ["rank" => 10, "suit" => "heart"],
            ["rank" => 10, "suit" => "club"],

        ];
        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("Full House", $rank);
    }

    public function testFourOfAKind(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 14, "suit" => "heart"],
            ["rank" => 14, "suit" => "spade"],
            ["rank" => 14, "suit" => "club"],
            ["rank" => 14, "suit" => "diamond"],
            ["rank" => 10, "suit" => "club"],

        ];
        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("Four of a Kind", $rank);
    }

    public function testIsStraight(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 2, "suit" => "heart"],
            ["rank" => 3, "suit" => "spade"],
            ["rank" => 4, "suit" => "club"],
            ["rank" => 5, "suit" => "diamond"],
            ["rank" => 6, "suit" => "club"],

        ];
        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("Straight", $rank);
    }

    public function testIsFlush(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 13, "suit" => "heart"],
            ["rank" => 12, "suit" => "heart"],
            ["rank" => 10, "suit" => "heart"],
            ["rank" => 7, "suit" => "heart"],
            ["rank" => 9, "suit" => "heart"],

        ];
        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("Flush", $rank);
    }

    public function testIsStraightFlush(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 6, "suit" => "spade"],
            ["rank" => 7, "suit" => "spade"],
            ["rank" => 8, "suit" => "spade"],
            ["rank" => 9, "suit" => "spade"],
            ["rank" => 10, "suit" => "spade"],

        ];
        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("Straight Flush", $rank);
    }

    public function testIsRoyalFlush(): void
    {
        $poker = new FiveCardPoker();

        $hand = [
            ["rank" => 10, "suit" => "spade"],
            ["rank" => 11, "suit" => "spade"],
            ["rank" => 12, "suit" => "spade"],
            ["rank" => 13, "suit" => "spade"],
            ["rank" => 14, "suit" => "spade"],
        ];

        $rank = $poker->getPokerRank($hand);

        $this->assertEquals("Royal Flush", $rank);
    }

    public function testCompareHighCardPlayerWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 7, "suit" => "spade"],
            ["rank" => 8, "suit" => "heart"],
            ["rank" => 4, "suit" => "diamond"],
            ["rank" => 2, "suit" => "club"],
            ["rank" => 14, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 2, "suit" => "spade"],
            ["rank" => 5, "suit" => "club"],
            ["rank" => 8, "suit" => "club"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 13, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);

        $player = [
            ["rank" => 6, "suit" => "spade"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 5, "suit" => "diamond"],
            ["rank" => 2, "suit" => "club"],
            ["rank" => 13, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 2, "suit" => "spade"],
            ["rank" => 4, "suit" => "club"],
            ["rank" => 9, "suit" => "club"],
            ["rank" => 6, "suit" => "heart"],
            ["rank" => 13, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);
    }

    public function testCompareHighCardComWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 7, "suit" => "spade"],
            ["rank" => 8, "suit" => "heart"],
            ["rank" => 4, "suit" => "diamond"],
            ["rank" => 2, "suit" => "club"],
            ["rank" => 13, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 2, "suit" => "spade"],
            ["rank" => 5, "suit" => "club"],
            ["rank" => 8, "suit" => "club"],
            ["rank" => 11, "suit" => "heart"],
            ["rank" => 14, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);

        $player = [
            ["rank" => 8, "suit" => "spade"],
            ["rank" => 11, "suit" => "heart"],
            ["rank" => 4, "suit" => "diamond"],
            ["rank" => 2, "suit" => "club"],
            ["rank" => 14, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 2, "suit" => "spade"],
            ["rank" => 5, "suit" => "club"],
            ["rank" => 8, "suit" => "club"],
            ["rank" => 11, "suit" => "heart"],
            ["rank" => 14, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);
    }

    public function testCompareHighCardDraw(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 2, "suit" => "spade"],
            ["rank" => 8, "suit" => "heart"],
            ["rank" => 5, "suit" => "diamond"],
            ["rank" => 11, "suit" => "club"],
            ["rank" => 14, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 2, "suit" => "spade"],
            ["rank" => 5, "suit" => "club"],
            ["rank" => 8, "suit" => "club"],
            ["rank" => 11, "suit" => "heart"],
            ["rank" => 14, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Oavgjort", $msg);
    }

    public function testCompareOnePairPlayerWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 7, "suit" => "diamond"],
            ["rank" => 7, "suit" => "heart"],
            ["rank" => 4, "suit" => "diamond"],
            ["rank" => 2, "suit" => "club"],
            ["rank" => 13, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 4, "suit" => "spade"],
            ["rank" => 4, "suit" => "club"],
            ["rank" => 1, "suit" => "spade"],
            ["rank" => 3, "suit" => "heart"],
            ["rank" => 12, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);

        $player = [
            ["rank" => 9, "suit" => "spade"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 4, "suit" => "diamond"],
            ["rank" => 6, "suit" => "club"],
            ["rank" => 14, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 9, "suit" => "spade"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 6, "suit" => "club"],
            ["rank" => 3, "suit" => "heart"],
            ["rank" => 14, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);
    }

    public function testCompareOnePairComWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 7, "suit" => "diamond"],
            ["rank" => 2, "suit" => "heart"],
            ["rank" => 4, "suit" => "diamond"],
            ["rank" => 11, "suit" => "club"],
            ["rank" => 11, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 1, "suit" => "spade"],
            ["rank" => 3, "suit" => "club"],
            ["rank" => 4, "suit" => "spade"],
            ["rank" => 12, "suit" => "heart"],
            ["rank" => 12, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);

        $player = [
            ["rank" => 8, "suit" => "spade"],
            ["rank" => 10, "suit" => "heart"],
            ["rank" => 9, "suit" => "diamond"],
            ["rank" => 10, "suit" => "club"],
            ["rank" => 5, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 6, "suit" => "spade"],
            ["rank" => 10, "suit" => "heart"],
            ["rank" => 9, "suit" => "club"],
            ["rank" => 8, "suit" => "heart"],
            ["rank" => 10, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);
    }

    public function testCompareOnePairDraw(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 10, "suit" => "diamond"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 10, "suit" => "diamond"],
            ["rank" => 8, "suit" => "club"],
            ["rank" => 7, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 7, "suit" => "spade"],
            ["rank" => 10, "suit" => "club"],
            ["rank" => 10, "suit" => "spade"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 8, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Oavgjort", $msg);
    }

    public function testCompareTwoPairPlayerWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 12, "suit" => "diamond"],
            ["rank" => 12, "suit" => "heart"],
            ["rank" => 11, "suit" => "diamond"],
            ["rank" => 8, "suit" => "club"],
            ["rank" => 11, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 9, "suit" => "spade"],
            ["rank" => 10, "suit" => "club"],
            ["rank" => 10, "suit" => "spade"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 8, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);

        $player = [
            ["rank" => 11, "suit" => "diamond"],
            ["rank" => 11, "suit" => "heart"],
            ["rank" => 9, "suit" => "diamond"],
            ["rank" => 9, "suit" => "club"],
            ["rank" => 7, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 9, "suit" => "spade"],
            ["rank" => 11, "suit" => "club"],
            ["rank" => 11, "suit" => "spade"],
            ["rank" => 5, "suit" => "heart"],
            ["rank" => 9, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);

        $player = [
            ["rank" => 13, "suit" => "diamond"],
            ["rank" => 13, "suit" => "heart"],
            ["rank" => 9, "suit" => "diamond"],
            ["rank" => 9, "suit" => "club"],
            ["rank" => 7, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 13, "suit" => "spade"],
            ["rank" => 8, "suit" => "club"],
            ["rank" => 13, "suit" => "spade"],
            ["rank" => 5, "suit" => "heart"],
            ["rank" => 8, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);
    }

    public function testCompareTwoPairComWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 11, "suit" => "diamond"],
            ["rank" => 13, "suit" => "heart"],
            ["rank" => 13, "suit" => "diamond"],
            ["rank" => 11, "suit" => "club"],
            ["rank" => 5, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 12, "suit" => "spade"],
            ["rank" => 14, "suit" => "club"],
            ["rank" => 14, "suit" => "spade"],
            ["rank" => 12, "suit" => "heart"],
            ["rank" => 8, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);

        $player = [
            ["rank" => 14, "suit" => "diamond"],
            ["rank" => 14, "suit" => "heart"],
            ["rank" => 10, "suit" => "diamond"],
            ["rank" => 10, "suit" => "club"],
            ["rank" => 7, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 14, "suit" => "spade"],
            ["rank" => 14, "suit" => "club"],
            ["rank" => 10, "suit" => "spade"],
            ["rank" => 10, "suit" => "heart"],
            ["rank" => 9, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);

        $player = [
            ["rank" => 6, "suit" => "diamond"],
            ["rank" => 6, "suit" => "heart"],
            ["rank" => 3, "suit" => "diamond"],
            ["rank" => 3, "suit" => "club"],
            ["rank" => 2, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 6, "suit" => "spade"],
            ["rank" => 6, "suit" => "club"],
            ["rank" => 4, "suit" => "spade"],
            ["rank" => 4, "suit" => "heart"],
            ["rank" => 9, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);
    }

    public function testCompareTwoPairDraw(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 7, "suit" => "diamond"],
            ["rank" => 7, "suit" => "heart"],
            ["rank" => 6, "suit" => "diamond"],
            ["rank" => 2, "suit" => "club"],
            ["rank" => 6, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 7, "suit" => "spade"],
            ["rank" => 6, "suit" => "club"],
            ["rank" => 7, "suit" => "spade"],
            ["rank" => 6, "suit" => "heart"],
            ["rank" => 2, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Oavgjort", $msg);
    }

    public function testCompareThreeOfAKindPlayerWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 9, "suit" => "diamond"],
            ["rank" => 7, "suit" => "heart"],
            ["rank" => 2, "suit" => "diamond"],
            ["rank" => 9, "suit" => "club"],
            ["rank" => 9, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 8, "suit" => "spade"],
            ["rank" => 8, "suit" => "club"],
            ["rank" => 4, "suit" => "spade"],
            ["rank" => 6, "suit" => "heart"],
            ["rank" => 8, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);
    }

    public function testCompareThreeOfAKindComWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 8, "suit" => "diamond"],
            ["rank" => 8, "suit" => "heart"],
            ["rank" => 2, "suit" => "diamond"],
            ["rank" => 8, "suit" => "club"],
            ["rank" => 3, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 8, "suit" => "spade"],
            ["rank" => 10, "suit" => "club"],
            ["rank" => 4, "suit" => "spade"],
            ["rank" => 10, "suit" => "heart"],
            ["rank" => 10, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);
    }

    public function testCompareFourOfAKindPlayerWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 9, "suit" => "diamond"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 2, "suit" => "diamond"],
            ["rank" => 9, "suit" => "club"],
            ["rank" => 9, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 6, "suit" => "spade"],
            ["rank" => 6, "suit" => "club"],
            ["rank" => 4, "suit" => "spade"],
            ["rank" => 6, "suit" => "heart"],
            ["rank" => 6, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);
    }

    public function testCompareFourOfAKindComWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 7, "suit" => "diamond"],
            ["rank" => 7, "suit" => "heart"],
            ["rank" => 7, "suit" => "diamond"],
            ["rank" => 7, "suit" => "club"],
            ["rank" => 9, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 11, "suit" => "spade"],
            ["rank" => 11, "suit" => "club"],
            ["rank" => 11, "suit" => "spade"],
            ["rank" => 6, "suit" => "heart"],
            ["rank" => 11, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);
    }

    public function testCompareHandPlayerWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 5, "suit" => "diamond"],
            ["rank" => 5, "suit" => "heart"],
            ["rank" => 1, "suit" => "diamond"],
            ["rank" => 3, "suit" => "club"],
            ["rank" => 2, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 14, "suit" => "spade"],
            ["rank" => 6, "suit" => "club"],
            ["rank" => 3, "suit" => "spade"],
            ["rank" => 2, "suit" => "heart"],
            ["rank" => 1, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Spelaren vann", $msg);
    }

    public function testCompareHandComWin(): void
    {
        $poker = new FiveCardPoker();
        $player = [
            ["rank" => 4, "suit" => "diamond"],
            ["rank" => 3, "suit" => "heart"],
            ["rank" => 1, "suit" => "diamond"],
            ["rank" => 2, "suit" => "club"],
            ["rank" => 2, "suit" => "spade"],
        ];

        $com = [
            ["rank" => 2, "suit" => "spade"],
            ["rank" => 6, "suit" => "club"],
            ["rank" => 6, "suit" => "spade"],
            ["rank" => 6, "suit" => "heart"],
            ["rank" => 1, "suit" => "diamond"],
        ];

        $poker->setPlayerHand($player);
        $poker->setComHand($com);
        $msg = $poker->compareHand();

        $this->assertEquals("Datorn vann", $msg);
    }

    public function testComLogicThreeOfAKind(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();
        $com = [
            ["rank" => 2, "suit" => "spade"],
            ["rank" => 5, "suit" => "club"],
            ["rank" => 5, "suit" => "spade"],
            ["rank" => 5, "suit" => "heart"],
            ["rank" => 1, "suit" => "diamond"],
        ];

        $poker->setComHand($com);
        $newCom = $poker->comLogic(400);

        $this->assertEquals(2, count($newCom));
    }

    public function testComLogicOnePair(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();
        $com = [
            ["rank" => 3, "suit" => "spade"],
            ["rank" => 14, "suit" => "club"],
            ["rank" => 14, "suit" => "spade"],
            ["rank" => 2, "suit" => "heart"],
            ["rank" => 8, "suit" => "diamond"],
        ];

        $poker->setComHand($com);
        $newCom = $poker->comLogic(250);

        $this->assertEquals(3, count($newCom));
    }

    public function testComLogicTwoPair(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();
        $com = [
            ["rank" => 12, "suit" => "spade"],
            ["rank" => 14, "suit" => "club"],
            ["rank" => 14, "suit" => "spade"],
            ["rank" => 9, "suit" => "heart"],
            ["rank" => 12, "suit" => "diamond"],
        ];

        $poker->setComHand($com);
        $newCom = $poker->comLogic(150);

        $this->assertEquals(1, count($newCom));
    }

    public function testComLogicHighCard(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();
        $com = [
            ["rank" => 7, "suit" => "spade"],
            ["rank" => 11, "suit" => "club"],
            ["rank" => 4, "suit" => "spade"],
            ["rank" => 3, "suit" => "heart"],
            ["rank" => 2, "suit" => "diamond"],
        ];

        $poker->setComHand($com);
        $newCom = $poker->comLogic(100);

        $this->assertEquals(5, count($newCom));
    }

    public function testComLogicHigherRank(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();
        $com = [
            ["rank" => 2, "suit" => "spade"],
            ["rank" => 3, "suit" => "spade"],
            ["rank" => 4, "suit" => "spade"],
            ["rank" => 5, "suit" => "spade"],
            ["rank" => 6, "suit" => "spade"],
        ];

        $poker->setComHand($com);
        $newCom = $poker->comLogic(0);

        $this->assertEquals(0, count($newCom));
    }

    public function testComLogicRaise(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();
        $poker->incPot(100);
        $poker->comLogic(0, 1);

        $this->assertNotEquals(100, $poker->getPot());
    }

    public function testComLogicCall(): void
    {
        $poker = new FiveCardPoker();
        $poker->dealHand();
        $playerPot = 100;
        $poker->incPot($playerPot);
        $poker->comLogic($playerPot, 2);

        $this->assertEquals(200, $poker->getPot());
    }
}
