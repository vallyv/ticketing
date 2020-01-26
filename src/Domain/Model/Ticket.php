<?php
namespace Domain\Model;

use Domain\DTO\TicketDto;
use Domain\User\Model\User;

class Ticket
{
    private $id;

    private $user;

    private $messages;

    private $created_at;

    private$updated_at;

    public static function OpenTicket(User $user, TicketDto $data): self
    {
        $now = new \DateTime('now');
        $ticket = new self();
        $ticket->user = $user;
        $ticket->messages  = $data->getMessage();
        $ticket->created_at = $now;
        $ticket->updated_at = $now;

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