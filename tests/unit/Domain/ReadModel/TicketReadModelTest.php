<?php

use Domain\DTO\TicketDto;
use Domain\Model\Message;
use Domain\Model\Ticket;
use Domain\ReadModel\TicketReadModel;
use Domain\User\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketReadModelTest extends WebTestCase
{

    public function testCanReadTicket()
    {

        $expected = [
            "user" => "utente",
             "status" => "open",
            "assignedTo" => "",
            "messages" => [
                [
                    "text" => "ciao",
                    "author" => "utente"
                ],                [
                    "text" => "Messaggio 1",
                    "author" => "utente"
                ],
                [
                    "text" => "Messaggio 2",
                    "author" => "utente"
                ],
                [
                    "text" => "Messaggio 3",
                    "author" => "utente"
                ],
                [
                    "text" => "Messaggio 4",
                    "author" => "utente"
                ],
                [
                    "text" => "Messaggio 5",
                    "author" => "utente"
                ]
            ]
        ];

        $messages = [];
        $user = $this->prophesize(User::class);
        $user->isAdmin()->willReturn(false);
        $user->getUsername()->willReturn('utente');

        $dto = TicketDto::fromArray(["messaggio" => "ciao"]);

        $ticket = Ticket::OpenTicket($user->reveal(), $dto);

        for ($i = 1; $i <= 5; $i++) {
            $dto = TicketDto::fromArray(["messaggio" => "Messaggio ".$i]);
            $ticket->AddMessage($dto);
        }

        $readmodel = TicketReadModel::create($ticket);

        $this->assertEquals($expected, $readmodel->serialize());
    }


}