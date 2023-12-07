<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    public function testLandingPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('nav');
        $this->assertSelectorExists('h1', 'Projekt - Femkortspoker');
        $this->assertSelectorExists('p');
    }

    public function testGamePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/game');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('nav');
        $this->assertSelectorExists('h1', 'Five Card Poker');
        $this->assertSelectorExists('input', 'Byt');
    }
}