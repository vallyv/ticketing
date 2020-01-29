<?php
namespace Domain\Model;

use Domain\ReadModel\TicketReadModel;
use Domain\User\Model\User;

class Message
{
    private $id;
    private $text;
    private $author;
    private $ticket;
    private $created_at;

    public static function create(User $author, string $text)
    {
        $message = new self();
        $message->text = $text;
        $message->author = $author;
        $message->created_at = new \DateTime('now');

        return $message;
    }

    public function joinTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function serialize()
    {
        return [
            "text" => $this->text,
            "author" => $this->author->getUsername()
        ];
    }
}