<?php
namespace Domain\User\Model;

use Domain\DTO\TicketDto;

class Ticket
{
    private $user;
    private $message;

    public static function OpenTicket(User $user, TicketDto $data): self
    {
        $ticket = new self();
        $ticket->user = $user;
        $ticket->message  = $data->getMessage();

        return $ticket;
    }
}