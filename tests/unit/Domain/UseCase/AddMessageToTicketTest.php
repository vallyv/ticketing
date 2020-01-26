<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\Repository\TicketRepository;
use Domain\UseCase\AddMessageToTicket;
use Domain\UseCase\OpenTicket;
use Domain\User\Model\User;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddMessageToTicketTest extends WebTestCase
{
    public function testUserCanAddMessageTicket()
    {
        $expected = [
            "user" => "utente",
            "message" => ["primo messaggio","secondo messaggio" ],
            "status" => "open",
            'assignedTo' => ''
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $dto = TicketDto::fromArray(["messaggio" => "secondo messaggio"]);
        $ticket = Ticket::OpenTicket($user->reveal(), TicketDto::fromArray(["messaggio" => "primo messaggio"]));

        $repo = $this->prophesize(TicketRepository::class);
        $repo->findByUserAndId($user->reveal(), 1)->willReturn($ticket);
        $repo->save(Argument::any())->shouldBeCalled();

        $useCase = new AddMessageToTicket($repo->reveal());

        $ticket = $useCase->execute(1, $user->reveal(), $dto);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $ticket->serialize());
    }
}