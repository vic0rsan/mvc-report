<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    private $card;

    public function setUp(): void
    {
        $this->card = new CardGraphic("spade", "ace", 14);
    }

    public function testGetCardRep(): void
    {
        $this->assertSame("🂡", $this->card->getCardRep());
    }
}
