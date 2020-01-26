<?php

use Domain\DTO\TicketDto;
use Domain\User\Model\Ticket;
use Domain\User\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketTest extends WebTestCase
{
    public function testUserCanCreateTicket()
    {
        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);

        $dto = TicketDto::fromArray(["messaggio" => "ciao"]);

        $ticket = Ticket::OpenTicket($user->reveal(), $dto);

        $this->assertInstanceOf(Ticket::class, $ticket);

    }
}