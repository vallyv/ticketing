<?php

use AppBundle\Tests\BaseWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AddMessagesTicketControllerTest extends BaseWebTestCase
{
    protected $client = null;

    public function setUp()
    {
        parent::setUp();
    }

    public function testUserCantAddMessageToNonExistentTicketPost()
    {
        $this->login("user");

        $data = ["messaggio" => "secondo messaggio"];

        $this->client->request('POST', '/ticket/100', $data);

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testUserCanAddMessageToTicketPost()
    {
        $this->login("user");

        $data = ["messaggio" =>"secondo messaggio"];

        $this->client->request('POST', '/ticket/1', $data);
        $response = $this->client->getResponse()->getContent();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"user":"user","message":["primo messaggio","secondo messaggio"],"status":"open","assignedTo":""}', $response);
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