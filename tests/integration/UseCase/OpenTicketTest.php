<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\ReadModel\TicketReadModel;
use Domain\Repository\TicketRepository;
use Domain\UseCase\OpenTicket;
use Domain\UseCase\SendAdminNotifications;
use Domain\User\Model\User;
use Domain\User\Repository\UserRepository;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OpenTicketTest extends WebTestCase
{
    public function testUserCanCreateTicket()
    {
        $expected = [
            "user" => "utente",
            "messages" => [
                ["text" => "ciao", "author" => "utente"]
            ],
            "status" => "open",
            'assignedTo' => ''
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');
        $user->hasPushNotification()->willReturn(true);
        $user->hasSMSNotification()->willReturn(true);

        $ticketRepo = $this->prophesize(TicketRepository::class);
        $userRepo = $this->prophesize(UserRepository::class);
        $userRepo->loadAdmins(Argument::any())->willReturn([$user]);

        $notificationSender = $this->prophesize(SendAdminNotifications::class);
        $notificationSender->execute(Argument::any())->shouldBeCalled();

        $dto = TicketDto::fromArray(["messaggio" => "ciao"]);
        $useCase = new OpenTicket($ticketRepo->reveal(), $notificationSender->reveal());
        $ticket = $useCase->execute($user->reveal(), $dto);
        $readModel = TicketReadModel::create($ticket);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $readModel->serialize());
    }
}