<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testAdmin()
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertResponseRedirects();
    }

    public function testProducts()
    {
        $client = static::createClient();
        $client->request('GET', '/products');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Nos Produits :');
    }
}
