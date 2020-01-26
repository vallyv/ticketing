<?php
namespace Domain\User\Model;

class Ticket
{
    private $user;

    public static function OpenTicket(User $user): self
    {
        $ticket = new self();
        $ticket->user = $user;

        return $ticket;
    }
}