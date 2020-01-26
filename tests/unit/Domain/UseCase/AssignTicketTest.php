<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\Repository\TicketRepository;
use Domain\UseCase\AddMessageToTicket;
use Domain\UseCase\AssignTicket;
use Domain\UseCase\OpenTicket;
use Domain\User\Model\User;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssignTicketTest extends WebTestCase
{
    /** @expectedException Exception
     * @expectedExceptionMessage Non hai i permessi
     */
    public function testTicketCantAssignToUser()
    {
        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $dto = TicketDto::fromArray(["messaggio" => "primo messaggio"]);
        $ticket = Ticket::OpenTicket($user->reveal(), $dto);

        $repo = $this->prophesize(TicketRepository::class);
        $repo->findById(1)->willReturn($ticket);

        $useCase = new AssignTicket($repo->reveal());

        $ticket = $useCase->execute(1, $user->reveal());
    }

    public function testTicketCanAsignedToAdmin()
    {
        $expected = [
            "user" => "utente",
            "message" => ["primo messaggio"],
            "status" => "assigned",
            "assignedTo" => 'admin'
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $admin = $this->prophesize(User::class);
        $admin->isAdmin()->willReturn(true);
        $admin->getUsername()->willReturn('admin');

        $dto = TicketDto::fromArray(["messaggio" => "primo messaggio"]);
        $ticket = Ticket::OpenTicket($user->reveal(), $dto);

        $repo = $this->prophesize(TicketRepository::class);
        $repo->findById(1)->willReturn($ticket);
        $repo->save(Argument::any())->shouldBeCalled();

        $useCase = new AssignTicket($repo->reveal());

        $ticket = $useCase->execute(1, $admin->reveal());

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $ticket->serialize());
    }
}