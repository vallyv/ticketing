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

    private $assigned;

    private $status;

    private $messages;

    private $created_at;

    private $updated_at;

    public static function OpenTicket(User $user, TicketDto $data): self
    {
        $now = new \DateTime('now');
        $ticket = new self();
        $ticket->user = $user;
        $ticket->status = self::STATUS_OPEN;
        $ticket->created_at = $now;
        $ticket->updated_at = $now;

        $ticket->AddMessage($data);

        return $ticket;
    }

    public function setUpdateTime(\DateTime $date)
    {
        $this->updated_at = $date;
    }

    public function close()
    {
        $this->status = self::STATUS_CLOSE;
        $this->updated_at = new \DateTime('now');
    }

    public function assign(User $user)
    {
        $this->assigned = $user;
        $this->status = self::STATUS_ASSIGNED;
        $this->updated_at = new \DateTime('now');
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isAssigned(): bool
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    public function isUsersTicket(User $user): bool
    {
        return $this->assigned === $user;
    }

    public function AddMessage(TicketDto $data)
    {
        // WIP -> no, passare utente loggato
        $message = Message::create($this->user, $data->getMessage());
        $message->joinTicket($this);
        $this->messages[] = $message;
    }

    public function getMessages()
    {
        return $this->messages;
    }
    public function serialize()
    {
        return [
            "user" => $this->user->getUsername(),
            "status" => $this->status,
            "assignedTo" => $this->assigned ? $this->assigned->getUsername() : ''
        ];
    }
}