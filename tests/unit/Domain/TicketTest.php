<?php

use Domain\User\Model\Ticket;
use Domain\User\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketTest extends WebTestCase
{
    public function testUserCanCreateTicket()
    {
        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);

        $ticket = Ticket::OpenTicket($user->reveal());

        $this->assertInstanceOf(Ticket::class, $ticket);

    }
}