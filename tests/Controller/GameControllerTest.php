<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Cards\Game21;

/**
 * Test cases for class GameController.
 */
class GameControllerTest extends WebTestCase
{
    public function testLandingPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1', 'Game 21');
        $this->assertSelectorExists('ul');        
    }

    public function testGameStartFromLandingPage(): void
    {
        $client = static::createClient();
        $client->request('POST', '/game/init');
        $response = $client->request('GET', '/game/start');

        $this->assertSelectorTextSame('h2', 'Spelaren');
        $this->assertCount(2, $response->filter('.status'));
        $this->assertCount(2, $response->filter('.card'));
        $this->assertCount(2, $response->filter('input'));
    }

    public function testGameStartDirectly(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $response = $client->request('GET', '/game/start');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h2', 'Spelaren');
        $this->assertCount(2, $response->filter('.status'));
        $this->assertCount(2, $response->filter('.card'));
        $this->assertCount(2, $response->filter('input'));
    }
}