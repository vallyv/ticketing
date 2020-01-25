<?php
namespace Tests\Domain;

use Domain\User\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    public function testUserNotAdmin()
    {
        $user = User::create('nome', 'password', 'email');

        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->isAdmin());
    }

    public function testUserIsAdmin()
    {
        $user = User::create('nome', 'password', 'email', 'admin');

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->isAdmin());
    }
}