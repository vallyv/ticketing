<?php

use AppBundle\Tests\BaseWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class CloseTicketControllerTest extends BaseWebTestCase
{
    protected $client = null;

    public function setUp()
    {
        parent::setUp();
    }

    public function testUserCantReadNonExistentTicketGet()
    {
        $this->login("user");

        $this->client->request('GET', '/ticket/close/100');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testUserCantReadTicketFromAnotherUserGet()
    {
        $this->login("user");

        $this->client->request('GET', '/ticket/close/2');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testUserCanAddMessageToTicketGet()
    {
        $this->login("user");

        $this->client->request('GET', '/ticket/close/1');
        $response = $this->client->getResponse()->getContent();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"user":"user","message":["primo messaggio"],"status":"close","assignedTo":""}', $response);
    }

    private function login($username)
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken($username, 'admin', $firewall, array('ROLE_USER'));

        $this->client->getContainer()->get('security.token_storage')->setToken($token);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

}