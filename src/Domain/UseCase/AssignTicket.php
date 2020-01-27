<?php
namespace Domain\UseCase;

use Domain\DTO\TicketDto;
use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class AssignTicket
{
    private $repository;

    public function __construct(TicketRepository $repo)
    {
        $this->repository = $repo;
    }

    public function execute(int $id, User $user)
    {
        if (!$user->isAdmin()){
            throw new \Exception('Non hai i permessi');
        }

        $ticket = $this->repository->findOpenById($id);

        if(is_null($ticket)){
            throw new \Exception('Ticket inesistente');
        }

        if ($ticket->isAssigned()){
            throw new \Exception('Ticket gia assegnato');
        }

        $ticket->assign($user);

        $this->repository->save($ticket);

        return $ticket;

    }

}