<?php
namespace Domain\Model;

use Domain\DTO\TicketDto;
use Domain\User\Model\User;

class Ticket
{
    private $id;
    private $user;
    private $messages;

    public static function OpenTicket(User $user, TicketDto $data): self
    {
        $ticket = new self();
        $ticket->user = $user;
        $ticket->messages  = $data->getMessage();

        return $ticket;
    }

    public function serialize()
    {
        return [
            "user" => $this->user->getUsername(),
            "message" => $this->messages
        ];
    }
}