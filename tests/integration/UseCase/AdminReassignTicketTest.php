<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\ReadModel\TicketReadModel;
use Domain\Repository\TicketRepository;
use Domain\UseCase\AdminReassignTicket;
use Domain\UseCase\AssignTicket;
use Domain\User\Model\User;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminReassignTicketTest extends WebTestCase
{
    public function testAdminCanCloseOpenOrAssignedTicket()
    {
        $expected = [
            "user" => "admin 1",
            "status" => "assigned",
            'assignedTo' => "admin 2",
            "messages" => [
                ["text" => "primo messaggio", "author" => "admin 1"],
            ],
        ];

        $admin = $this->prophesize(User::class);
        $admin->isAdmin()->willReturn(true);
        $admin->getUsername()->willReturn('admin 1');

        $admin2 = $this->prophesize(User::class);
        $admin2->isAdmin()->willReturn(true);
        $admin2->getUsername()->willReturn('admin 2');

        $dto = TicketDto::fromArray(["messaggio" => "primo messaggio"]);
        $ticket = Ticket::OpenTicket($admin->reveal(), $dto);

        $repo = $this->prophesize(TicketRepository::class);
        $repo->findOneById(1)->willReturn($ticket);
        $repo->findOpenById(1)->willReturn($ticket);
        $repo->save(Argument::any())->shouldBeCalled();

        $useCase = new AssignTicket($repo->reveal());
        $useCase->execute(1, $admin->reveal());

        $useCase = new AdminReassignTicket($repo->reveal());

        $ticket = $useCase->execute(1, $admin->reveal(), $admin2->reveal());

        $readModel = TicketReadModel::create($ticket);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $readModel->serialize());
    }
}