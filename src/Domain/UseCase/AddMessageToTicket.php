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
        $ticket = $this->repository->findByUserAndId($user, $id);

        $ticket->addMessage($data->getMessage());
        $ticket->setUpdateTime(new \DateTime('now'));

        $this->repository->save($ticket);

        return $ticket;

    }

}