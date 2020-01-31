<?php
namespace Domain\UseCase;

use Domain\DTO\TicketDto;
use Domain\Repository\TicketRepository;
use Domain\User\Model\User;

class AddMessageToTicket
{
    private $repository;
    private $adminNotifications;

    public function __construct(TicketRepository $repo, SendAdminNotifications $adminNotifications, SendSingleUserNotifications $notifications)
    {
        $this->repository = $repo;
        $this->adminNotifications = $adminNotifications;
        $this->notifications = $notifications;
    }

    public function execute(int $id, User $user, TicketDto $data)
    {
        $ticket = $this->repository->findNotCloseByUserAndId($user, $id);

        if(is_null($ticket)){
            return;
        }

        $message = 'Nuovo messaggio nel ticket' . $id;

        if (!$ticket->isAssigned()){
            $this->adminNotifications->execute($message);
        }

        if ($ticket->getAssigned()){
            $this->notifications->execute($ticket->getAssigned(), $message);
        }


        $ticket->addMessage($data);

        $this->repository->save($ticket);

        return $ticket;

    }

}