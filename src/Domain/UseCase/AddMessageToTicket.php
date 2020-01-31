<?php
namespace Domain\UseCase;

use Domain\DTO\TicketDto;
use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class AddMessageToTicket
{
    private $repository;
    private $adminNotifications;

    public function __construct(TicketRepository $repo, SendAdminNotifications $adminNotifications)
    {
        $this->repository = $repo;
        $this->adminNotifications = $adminNotifications;
    }

    public function execute(int $id, User $user, TicketDto $data)
    {
        $ticket = $this->repository->findNotCloseByUserAndId($user, $id);

        if(is_null($ticket)){
            return;
        }
        if (!$ticket->isAssigned()){
            $this->adminNotifications->execute('Nuovo messaggio nel ticket'.$id);
        }


        $ticket->addMessage($data);

        $this->repository->save($ticket);

        return $ticket;

    }

}