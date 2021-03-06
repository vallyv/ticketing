<?php

use AppBundle\Tests\BaseWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class OpenTicketControllerTest extends BaseWebTestCase
{
    protected $client = null;

    public function setUp()
    {
        parent::setUp();

    }

    public function testUserNotLoggedOpenTicketPost()
    {
        $data = ["messaggio" =>"ciao"];

        $this->client->request('POST', '/ticket', $data);

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminOpenTicketPost()
    {
        $this->adminLogin();

        $data = ["messaggio" =>"ciao"];

        $this->client->request('POST', '/ticket', $data);
        $response = $this->client->getResponse()->getContent();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"user":"admin","status":"open","assignedTo":"","messages":[{"text":"ciao","author":"admin"}]}', $response);
    }

    public function testUserCanOpenTicketPost()
    {
        $this->login();

        $data = ["messaggio" =>"ciao"];

        $this->client->request('POST', '/ticket', $data);
        $response = $this->client->getResponse()->getContent();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"user":"username1","status":"open","assignedTo":"","messages":[{"text":"ciao","author":"username1"}]}', $response);
    }

    private function login()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken('username1', 'admin', $firewall, array('ROLE_USER'));

        $this->client->getContainer()->get('security.token_storage')->setToken($token);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    private function adminLogin()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken('admin', 'admin', $firewall, array('ROLE_ADMIN', 'ROLE_USER'));

        $this->client->getContainer()->get('security.token_storage')->setToken($token);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}