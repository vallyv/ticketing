<?php
namespace Domain\UseCase;

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class OpenTicket
{
    private $ticketRepo;
    private $notificationSender;

    public function __construct(TicketRepository $ticketRepo, SendAdminNotifications $notification)
    {
        $this->ticketRepo = $ticketRepo;
        $this->notificationSender = $notification;
    }

    public function execute(User $user, TicketDto $data)
    {
        $ticket = Ticket::OpenTicket($user, $data);

        $this->ticketRepo->save($ticket);

        $this->notificationSender->execute('Ticket open');

        return $ticket;

    }

}