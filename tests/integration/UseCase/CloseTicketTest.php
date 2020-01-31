<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\Repository\TicketRepository;
use Domain\UseCase\CloseTicket;
use Domain\UseCase\OpenTicket;
use Domain\UseCase\SendAdminNotifications;
use Domain\User\Model\User;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CloseTicketTest extends WebTestCase
{
    public function testUserCanCloseTicket()
    {
        $expected = [
            "user" => "utente",
            "status" => "close",
            'assignedTo' => ''
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $dto = TicketDto::fromArray(["messaggio" => "primo messaggio"]);
        $ticket = Ticket::OpenTicket($user->reveal(), $dto);

        $repo = $this->prophesize(TicketRepository::class);
        $repo->findNotCloseByUserAndId($user->reveal(), 1)->willReturn($ticket);
        $repo->save(Argument::any())->shouldBeCalled();
        $notificationSender = $this->prophesize(SendAdminNotifications::class);
        $notificationSender->execute(Argument::any())->shouldBeCalled();

        $useCase = new CloseTicket($repo->reveal(), $notificationSender->reveal());

        $ticket = $useCase->execute(1, $user->reveal());

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $ticket->serialize());
    }
}