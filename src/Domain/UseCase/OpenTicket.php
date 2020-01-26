<?php
namespace Domain\UseCase;

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class OpenTicket
{
    private $repository;

    public function __construct(TicketRepository $repo)
    {
        $this->repository = $repo;
    }

    public function execute(User $user, TicketDto $data)
    {
        $ticket = Ticket::OpenTicket($user, $data);

        $this->repository->save($ticket);

        return $ticket;

    }

}