<?php
namespace Domain\UseCase;

use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class AdminCloseTicket
{
    private $repository;

    public function __construct(TicketRepository $repo)
    {
        $this->repository = $repo;
    }

    public function execute(int $id, User $admin)
    {
        $ticket = $this->repository->findOneById($id);

        if(is_null($ticket)){
            return;
        }

        if(!$ticket->isUsersTicket($admin) && !$ticket->isOpen()){
            return;
        }

        $ticket->close();

        $this->repository->save($ticket);

        return $ticket;

    }

}