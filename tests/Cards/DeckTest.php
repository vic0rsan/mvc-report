<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck.
*/

class DeckTest extends TestCase
{
    public function testCreateDeck(): void
    {
        $deck = new Deck();
        $deck->createDeck();

        $this->assertCount(52, $deck->getDeck());
        $this->assertInstanceOf("\App\Cards\Card", $deck->getDeck()[0]);
    }

    public function testDraw(): void
    {
        $deck = new Deck();
        $deck->createDeck();

        $this->assertCount(52, $deck->getDeck());
        $deck->draw();
        $this->assertCount(51, $deck->getDeck());
        $deck->draw(5);
        $this->assertCount(51 - 5, $deck->getDeck());
    }

    public function testCardLeft(): void
    {
        $deck = new Deck();
        $deck->createDeck();

        $this->assertSame(52, $deck->cardLeft());
        $deck->draw(10);
        $this->assertSame(52 - 10, $deck->cardLeft());
    }

    public function testShuffleDeck(): void
    {
        $deck = new Deck();
        $deck->createDeck();

        $newDeck = new Deck();
        $newDeck->createDeck();
        $newDeck->shuffleDeck();

        $this->assertNotSame($deck, $newDeck);
    }
}
