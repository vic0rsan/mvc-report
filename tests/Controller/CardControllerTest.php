<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for class CardController.
 */
class CardControllerTest extends WebTestCase
{

    public function testLandingPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1', 'Card');
        $this->assertSelectorExists('ul');
    }

    public function testDeck(): void 
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1', 'Deck');
    }

    public function testShuffle(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1', 'Shuffled deck');
    }

    public function testDraw(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');
        $client->request('GET', '/card/deck/draw/3');
        $this->assertResponseIsSuccessful();
    } 
}