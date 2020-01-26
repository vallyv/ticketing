<?php
namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\User\Model\User;

class TicketFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = User::create('user', 'user', 'email');
        $manager->persist($user);

        $ticketDto = TicketDto::fromArray([
           "messaggio" => "primo messaggio"
        ]);

        $ticket = Ticket::OpenTicket($user, $ticketDto);

        $manager->persist($ticket);

        $manager->flush();
    }
}