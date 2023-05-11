<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
*/

class CardHandTest extends TestCase
{
    public function testAdd(): void
    {
        $hand = new CardHand();
        $hand->add([
            new CardGraphic("heart", "5", 5),
            new CardGraphic("club", "10", 5)
        ]);
        $this->assertCount(2, $hand->getHand());
        
        $hand->add([new CardGraphic("diamond", "queen", 12)]);

        $this->assertCount(3, $hand->getHand());
    }
}