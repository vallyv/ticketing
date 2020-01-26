<?php
namespace Domain\Model;

use Domain\DTO\TicketDto;
use Domain\User\Model\User;

class Ticket
{
    const STATUS_OPEN = "open";

    const STATUS_CLOSE = "close";

    const STATUS_ASSIGNED = "assigned";

    private $id;

    private $user;

    private $messages;

    private $status;

    private $created_at;

    private$updated_at;

    public static function OpenTicket(User $user, TicketDto $data): self
    {
        $now = new \DateTime('now');
        $ticket = new self();
        $ticket->user = $user;
        $ticket->status = self::STATUS_OPEN;
        $ticket->messages[]  = $data->getMessage();
        $ticket->created_at = $now;
        $ticket->updated_at = $now;

        return $ticket;
    }

    public function addMessage(string $message)
    {
        $this->messages[] = $message;
    }

    public function setUpdateTime(\DateTime $date)
    {
        $this->updated_at = $date;
    }

    public function serialize()
    {
        return [
            "user" => $this->user->getUsername(),
            "message" => $this->messages,
            "status" => $this->status
        ];
    }
}