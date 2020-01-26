<?php
namespace Tests\features;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{
    public function testOpenTicketPost()
    {
        $data = ["messaggio" =>"ciao"];
        $client = static::createClient();

        $client->request('POST', '/ticket', $data);
        $response = $client->getResponse()->getContent();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}