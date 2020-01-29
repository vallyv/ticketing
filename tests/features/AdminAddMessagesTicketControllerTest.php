<?php

use AppBundle\Tests\BaseWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AdminAddMessagesTicketControllerTest extends BaseWebTestCase
{
    protected $client = null;

    public function setUp()
    {
        parent::setUp();
    }

    public function testAdminCantAddMessageToNonExistentTicketPost()
    {
        $this->login();

        $data = ["messaggio" =>"secondo messaggio"];

        $this->client->request('POST', 'admin/ticket/100', $data);

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminCanAddMessageToTicketPost()
    {
        $this->login();

        $data = ["messaggio" =>"secondo messaggio"];

        $this->client->request('POST', 'admin/ticket/1', $data);
        $response = $this->client->getResponse()->getContent();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"user":"user","status":"assigned","assignedTo":"admin","messages":[{"text":"primo messaggio","author":"user"},{"text":"secondo messaggio","author":"user"}]}', $response);
    }

    public function testUserCanAddMessageToAssignedTicketPost()
    {
        $this->login();

        $data = ["messaggio" =>"secondo messaggio"];

        $this->client->request('POST', 'admin/ticket/2', $data);
        $response = $this->client->getResponse()->getContent();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"user":"user","status":"assigned","assignedTo":"admin","messages":[{"text":"primo messaggio","author":"user"},{"text":"secondo messaggio","author":"user"}]}', $response);
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