<?php
namespace Domain\ReadModel;

use Domain\Model\Ticket;

class TicketReadModel
{
    private $ticket;

    public static function create(Ticket $ticket)
    {
        $rm = new self();
        $rm->ticket = $ticket;

        return $rm;
    }

    public function serialize()
    {
        $messagesSerialized = [];

        foreach ($this->ticket->getMessages() as $message) {
            $messagesSerialized[] = $message->serialize();
        }
        $data = $this->ticket->serialize();
        $data["messages"] = $messagesSerialized;

        return $data;
    }

}