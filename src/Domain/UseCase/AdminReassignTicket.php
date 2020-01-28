<?php
namespace Domain\UseCase;

use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class AdminReassignTicket
{
    private $repository;

    public function __construct(TicketRepository $repo)
    {
        $this->repository = $repo;
    }

    public function execute(int $id, User $admin, User $newAdmin)
    {
        $ticket = $this->repository->findOneById($id);

        if(is_null($ticket)){
            return;
        }

        if(!$ticket->isUsersTicket($admin) && !$ticket->isOpen()){
            return;
        }

        $ticket->assign($newAdmin);

        $this->repository->save($ticket);

        return $ticket;

    }

}