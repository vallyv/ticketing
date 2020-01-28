<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\Repository\TicketRepository;
use Domain\UseCase\AddMessageToTicket;
use Domain\UseCase\AdminAddMessageToTicket;
use Domain\UseCase\OpenTicket;
use Domain\User\Model\User;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminAddMessageToTicketTest extends WebTestCase
{
    public function testUserCanAddMessageTicket()
    {
        $expected = [
            "user" => "user",
            "message" => ["primo messaggio","secondo messaggio" ],
            "status" => "assigned",
            'assignedTo' => 'admin'
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('user');

        $admin = $this->prophesize(User::class);
        $admin->isAdmin()->willReturn(true);
        $admin->getUsername()->willReturn('admin');

        $dto = TicketDto::fromArray(["messaggio" => "secondo messaggio"]);
        $ticket = Ticket::OpenTicket($user->reveal(), TicketDto::fromArray(["messaggio" => "primo messaggio"]));

        $repo = $this->prophesize(TicketRepository::class);
        $repo->findOneById(1)->willReturn($ticket);
        $repo->save(Argument::any())->shouldBeCalled();

        $useCase = new AdminAddMessageToTicket($repo->reveal());

        $ticket = $useCase->execute(1, $admin->reveal(), $dto);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $ticket->serialize());
    }
}