<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

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
        $this->assertSelectorExists('ul');
    }

    public function testDeck(): void 
    {
        $client = static::createClient();
        $response = $client->request('GET', '/card/deck');

        $this->assertResponseIsSuccessful();
        $this->assertCount(52, $response->filter('.card'));
    }

    public function testShuffle(): void
    {
        $client = static::createClient();
        $response = $client->request('GET', '/card/deck/shuffle');

        $this->assertResponseIsSuccessful();
        $this->assertCount(52, $response->filter('.card'));
    }

    public function testDraw(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');
        $response = $client->request('GET', '/card/deck/draw/3');

        $this->assertResponseIsSuccessful();
        $this->assertCount(3, $response->filter('.card'));
    } 
}