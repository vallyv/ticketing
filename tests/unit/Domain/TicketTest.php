<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\User\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketTest extends WebTestCase
{
    public function testUserCanCreateTicket()
    {
        $expected = [
            "user" => "utente",
            "message" => ["ciao"],
            "status" => "open"
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $dto = TicketDto::fromArray(["messaggio" => "ciao"]);

        $ticket = Ticket::OpenTicket($user->reveal(), $dto);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $ticket->serialize());
    }
}