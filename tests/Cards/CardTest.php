<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * @var Card
     */
    private $card;

    public function setUp(): void
    {
        $this->card = new Card("spade", "ace", 14);
    }

    public function testGetSuite(): void
    {
        $this->assertSame('spade', $this->card->getSuite());
    }

    public function testGetRank(): void
    {
        $this->assertSame('ace', $this->card->getRank());
    }

    public function testGetPoint(): void
    {
        $this->assertSame(14, $this->card->getPoint());
    }
}
