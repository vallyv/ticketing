<?php
namespace Domain\Repository;

use Doctrine\ORM\EntityRepository;
use Domain\Model\Ticket;
use Domain\User\Model\User;

class TicketRepository extends EntityRepository
{

    public function findByUserAndId(User $user, int $id)
    {
        return $this->findOneBy([
            "user" => $user,
            "id" => $id
        ]);

    }

    public function findById(int $id)
    {
        return $this->findOneBy([
            "id" => $id
        ]);

    }

    public function save(Ticket $ticket)
    {
        $this->_em->persist($ticket);
        $this->_em->flush();
    }
}