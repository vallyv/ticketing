<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\Repository\TicketRepository;
use Domain\UseCase\CloseTicket;
use Domain\UseCase\OpenTicket;
use Domain\User\Model\User;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CloseTicketTest extends WebTestCase
{
    public function testUserCanCreateTicket()
    {
        $expected = [
            "user" => "utente",
            "message" => ["primo messaggio"],
            "status" => "close",
            'assignedTo' => ''
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $dto = TicketDto::fromArray(["messaggio" => "primo messaggio"]);
        $ticket = Ticket::OpenTicket($user->reveal(), $dto);

        $repo = $this->prophesize(TicketRepository::class);
        $repo->findByUserAndId($user->reveal(), 1)->willReturn($ticket);
        $repo->save(Argument::any())->shouldBeCalled();

        $useCase = new CloseTicket($repo->reveal());

        $ticket = $useCase->execute(1, $user->reveal());

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $ticket->serialize());
    }
}