<?php
namespace Domain\Repository;

use Doctrine\ORM\EntityRepository;
use Domain\Model\Ticket;
use Domain\User\Model\User;

class TicketRepository extends EntityRepository
{

    public function findNotCloseByUserAndId(User $user, int $id)
    {
        $q = $this->createQueryBuilder('t')
            ->where('t.user  = :user')
            ->andWhere('t.id = :id')
            ->andWhere('t.status  <> :status_close')
            ->setParameter('user', $user)
            ->setParameter('id', $id)
            ->setParameter('status_close', "close")
            ->getQuery();

        return $q->getOneOrNullResult();
    }

    public function findByUserAndId(User $user, int $id)
    {
        return $this->findOneBy([
            "id" => $id,
            "user" => $user
        ]);
    }

    public function findOpenById(int $id)
    {
        return $this->findOneBy([
            "id" => $id,
            "status" => 'open'
        ]);
    }

    public function save(Ticket $ticket)
    {
        $this->_em->persist($ticket);
        $this->_em->flush();
    }
}