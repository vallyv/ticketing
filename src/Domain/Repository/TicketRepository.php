<?php
namespace Domain\Repository;

use Doctrine\ORM\EntityRepository;
use Domain\Model\Ticket;

class TicketRepository extends EntityRepository
{
    public function save(Ticket $ticket)
    {
        $this->_em->persist($ticket);
        $this->_em->flush();
    }
}