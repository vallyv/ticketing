<?php

use AppBundle\Tests\BaseWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AdminCloseTicketControllerTest extends BaseWebTestCase
{
    protected $client = null;

    public function setUp()
    {
        parent::setUp();
    }

    public function testAdminCantCloseToNonExistentTicketPost()
    {
        $this->login();

        $data = ["messaggio" =>"secondo messaggio"];

        $this->client->request('GET', 'admin/ticket/close/100');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminCanAddMessageToTicketPost()
    {
        $this->login();

        $this->client->request('GET', 'admin/ticket/close/1');
        $response = $this->client->getResponse()->getContent();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"user":"user","message":["primo messaggio"],"status":"close","assignedTo":""}', $response);
    }

    public function testUserCanAddMessageToAssignedTicketPost()
    {
        $this->login();

        $this->client->request('GET', 'admin/ticket/close/2');
        $response = $this->client->getResponse()->getContent();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"user":"user","message":["primo messaggio"],"status":"close","assignedTo":"admin"}', $response);
    }

    private function login()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken('admin', 'admin', $firewall, array('ROLE_USER', 'ROLE_ADMIN'));

        $this->client->getContainer()->get('security.token_storage')->setToken($token);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

}