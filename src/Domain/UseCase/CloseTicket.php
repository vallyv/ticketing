<?php
namespace Domain\UseCase;

use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class CloseTicket
{
    private $repository;
    private $adminNotifications;

    public function __construct(TicketRepository $repo, SendAdminNotifications $adminNotifications)
    {
        $this->repository = $repo;
        $this->adminNotifications = $adminNotifications;
    }

    public function execute(int $id, User $user)
    {
        $ticket = $this->repository->findNotCloseByUserAndId($user, $id);

        if(is_null($ticket)){
            return;
        }

        if (!$ticket->isAssigned()){
            $this->adminNotifications->execute('Ticket chiuso '.$id);
        }

        $ticket->close();

        $this->repository->save($ticket);

        return $ticket;

    }

}