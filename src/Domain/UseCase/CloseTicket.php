<?php
namespace Domain\UseCase;

use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class CloseTicket
{
    private $repository;

    public function __construct(TicketRepository $repo)
    {
        $this->repository = $repo;
    }

    public function execute(int $id, User $user)
    {
        $ticket = $this->repository->findNotCloseByUserAndId($user, $id);

        if(is_null($ticket)){
            return;
        }

        $ticket->close();

        $this->repository->save($ticket);

        return $ticket;

    }

}