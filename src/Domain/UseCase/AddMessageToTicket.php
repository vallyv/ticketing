<?php
namespace Domain\UseCase;

use Domain\DTO\TicketDto;
use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class AddMessageToTicket
{
    private $repository;

    public function __construct(TicketRepository $repo)
    {
        $this->repository = $repo;
    }

    public function execute(int $id, User $user, TicketDto $data)
    {
        $ticket = $this->repository->findNotCloseByUserAndId($user, $id);

        if(is_null($ticket)){
            return;
        }

        $ticket->addMessage($data);

        $this->repository->save($ticket);

        return $ticket;

    }

}