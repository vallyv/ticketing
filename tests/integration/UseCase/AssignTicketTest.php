<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\ReadModel\TicketReadModel;
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
        $repo->findOpenById(1)->willReturn($ticket);

        $useCase = new AssignTicket($repo->reveal());

        $useCase->execute(1, $user->reveal());
    }

    /** @expectedException Exception
     * @expectedExceptionMessage Ticket gia assegnato
     */
    public function testTicketCantAsignedToAdminIfAlreadyAssigned()
    {
        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $admin = $this->prophesize(User::class);
        $admin->isAdmin()->willReturn(true);
        $admin->getUsername()->willReturn('admin');

        $dto = TicketDto::fromArray(["messaggio" => "primo messaggio"]);
        $ticket = Ticket::OpenTicket($user->reveal(), $dto);

        $repo = $this->prophesize(TicketRepository::class);
        $repo->findOpenById(1)->willReturn($ticket);
        $repo->save(Argument::any())->shouldBeCalled();

        $useCase = new AssignTicket($repo->reveal());

        $useCase->execute(1, $admin->reveal());
        $useCase->execute(1, $admin->reveal());
    }

    public function testTicketCanAsignedToAdmin()
    {
        $expected = [
            "user" => "utente",
            "messages" => [
                ["text" => "primo messaggio", "author" => "utente"],
            ],
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
        $repo->findOpenById(1)->willReturn($ticket);
        $repo->save(Argument::any())->shouldBeCalled();

        $useCase = new AssignTicket($repo->reveal());

        $ticket = $useCase->execute(1, $admin->reveal());
        $readModel = TicketReadModel::create($ticket);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $readModel->serialize());
    }
}