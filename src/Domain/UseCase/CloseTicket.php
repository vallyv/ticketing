<?php
namespace Domain\UseCase;

use Domain\DTO\TicketDto;
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
        $ticket = $this->repository->findByUserAndId($user, $id);

        $ticket->close();

        $this->repository->save($ticket);

        return $ticket;

    }

}