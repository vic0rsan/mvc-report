<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for class ApiCardController.
 */
class ApiCardControllerTest extends WebTestCase
{
    public function testJsonDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testJsonShuffle(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck/shuffle');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testJsonDraw(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');
        $client->request('GET', '/api/deck/draw/5');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }
}
