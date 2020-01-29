<?php

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\ReadModel\TicketReadModel;
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
            "messages" => [
                ["text" => "ciao", "author" => "utente"]
            ],
            "status" => "open",
            'assignedTo' => ''
        ];

        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $repo = $this->prophesize(TicketRepository::class);

        $dto = TicketDto::fromArray(["messaggio" => "ciao"]);
        $useCase = new OpenTicket($repo->reveal());
        $ticket = $useCase->execute($user->reveal(), $dto);
        $readModel = TicketReadModel::create($ticket);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($expected, $readModel->serialize());
    }
}