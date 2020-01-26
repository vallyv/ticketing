<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\Repository\TicketRepository;
use Domain\UseCase\OpenTicket;
use Domain\User\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OpenTicketTest extends WebTestCase
{
    public function testUserCanCreateTicket()
    {
        $expected = [
          "user" => "utente",
          "message" => ["ciao"]
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $repo = $this->prophesize(TicketRepository::class);

        $dto = TicketDto::fromArray(["messaggio" => "ciao"]);

        $useCase = new OpenTicket($repo->reveal());

        $ticket = $useCase->execute($user->reveal(), $dto);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $ticket->serialize());
    }
}