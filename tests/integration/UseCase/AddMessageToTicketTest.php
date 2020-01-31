<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\ReadModel\TicketReadModel;
use Domain\Repository\TicketRepository;
use Domain\UseCase\AddMessageToTicket;
use Domain\UseCase\OpenTicket;
use Domain\UseCase\SendAdminNotifications;
use Domain\User\Model\User;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddMessageToTicketTest extends WebTestCase
{
    public function testUserCanAddMessageTicket()
    {
        $expected = [
            "user" => "utente",
            "status" => "open",
            'assignedTo' => "",
            "messages" => [
                [
                  "text" => "primo messaggio",
                  "author" => "utente"
                ],
                [
                  "text" => "secondo messaggio",
                  "author" => "utente"
                ],
            ],
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $dto = TicketDto::fromArray(["messaggio" => "secondo messaggio"]);
        $ticket = Ticket::OpenTicket($user->reveal(), TicketDto::fromArray(["messaggio" => "primo messaggio"]));

        $repo = $this->prophesize(TicketRepository::class);
        $repo->findNotCloseByUserAndId($user->reveal(), 1)->willReturn($ticket);
        $repo->save(Argument::any())->shouldBeCalled();
        $notificationSender = $this->prophesize(SendAdminNotifications::class);
        $notificationSender->execute(Argument::any())->shouldBeCalled();

        $useCase = new AddMessageToTicket($repo->reveal(), $notificationSender->reveal());
        $ticket = $useCase->execute(1, $user->reveal(), $dto);
        $readModel = TicketReadModel::create($ticket);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $readModel->serialize());
    }
}