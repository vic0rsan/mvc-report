<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game21.
*/

class TestGame21 extends TestCase
{
    public function testPlayerDraw(): void
    {
        $game = new Game21();
        $game->getDeck()->createDeck();
        $game->playerDraw();

        $this->assertInstanceOf("\App\Cards\CardHand", $game->getPlayer());
        $this->assertNotEmpty($game->getPlayer()->getHand());
        $this->assertNotSame(0, $game->getPlayerPoint());
    }

    public function testBankDraw(): void
    {
        $game = new Game21();
        $game->getDeck()->createDeck();
        $game->bankDraw();

        $this->assertInstanceOf("\App\Cards\CardHand", $game->getbank());
        $this->assertNotEmpty($game->getBank()->getHand());
        $this->assertNotSame(0, $game->getBankPoint());
    }

    public function testSetGameover(): void
    {
        $game = new Game21();

        $this->assertFalse($game->getGameover());
        $game->setGameover();
        $this->assertTrue($game->getGameover());
    }

    public function testBankTurn(): void
    {
        $game = new Game21();
        $game->getDeck()->createDeck();

        $this->assertSame(0, $game->getBankPoint());
        $game->bankTurn();
        $this->assertGreaterThanOrEqual(17, $game->getBankPoint());
    }

    public function testComparePoints(): void
    {
        $game = new Game21();

        //Set player's cards sum to over 21
        //The bank should win.
        $game->setPlayerHand([
            new Card("spade", "7", 7),
            new Card("spade", "8", 8),
            new Card("diamond", "jack", 11)
        ]);

        $this->assertSame("Banken vann!", $game->comparePoints());
        $game->resetHand();

        //Set player's cards sum to 21
        //The player should win.
        $game->setPlayerHand([
            new Card("spade", "7", 7),
            new Card("spade", "3", 3),
            new Card("diamond", "jack", 11)
        ]);

        $this->assertSame("Spelaren vann!", $game->comparePoints());
        $game->resetHand();

        //Set bank's cards sum to over 21
        //The player should win.
        $game->setBankHand([
            new Card("club", "9", 9),
            new Card("spade", "jack", 11),
            new Card("diamond", "ace", 14)
        ]);

        $this->assertSame("Spelaren vann!", $game->comparePoints());
        $game->resetHand();

        //Set bank's cards sum to 17 or greater
        //Set player's sum to below 17
        //The bank should win.
        $game->setBankHand([
            new Card("club", "9", 9),
            new Card("spade", "4", 4),
            new Card("diamond", "5", 5)
        ]);

        $game->setPlayerHand([
            new Card("club", "3", 3),
            new Card("spade", "6", 6),
            new Card("diamond", "7", 7)
        ]);
        $this->assertSame("Banken vann!", $game->comparePoints());
        $game->resetHand();

        //Lastly, player and bank sum is equal
        // It should be a tie.
        $game->setBankHand([
            new Card("club", "9", 9),
            new Card("spade", "4", 4),
            new Card("diamond", "4", 4)
        ]);

        $game->setPlayerHand([
            new Card("club", "2", 2),
            new Card("spade", "5", 5),
            new Card("diamond", "10", 10)
        ]);
        $this->assertSame("Oavgjort!", $game->comparePoints());
    }
}
