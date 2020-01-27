<?php
namespace Domain\UseCase;

use Domain\DTO\TicketDto;
use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class AdminAddMessageToTicket
{
    private $repository;

    public function __construct(TicketRepository $repo)
    {
        $this->repository = $repo;
    }

    public function execute(int $id, User $admin, TicketDto $data)
    {
        $ticket = $this->repository->findOpenById($id);

        if(is_null($ticket)){
            return;
        }

        $ticket->addMessage($data->getMessage());
        $ticket->assign($admin);

        $this->repository->save($ticket);

        return $ticket;

    }

}