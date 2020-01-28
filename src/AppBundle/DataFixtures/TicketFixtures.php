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
        $admin = User::create('admin', 'admin', 'emailadmin', 'admin');
        $manager->persist($admin);

        $admin2 = User::create('admin2', 'admin', 'emailadmin', 'admin');
        $manager->persist($admin2);

        $user = User::create('user', 'user', 'email');
        $manager->persist($user);

        $user2 = User::create('user2', 'user', 'email');
        $manager->persist($user2);

        $ticketDto = TicketDto::fromArray([
           "messaggio" => "primo messaggio"
        ]);

        $ticket = Ticket::OpenTicket($user, $ticketDto);

        $manager->persist($ticket);

        $ticket = Ticket::OpenTicket($user, $ticketDto);
        $ticket->assign($admin);

        $manager->persist($ticket);

        $ticket = Ticket::OpenTicket($user2, $ticketDto);

        $manager->persist($ticket);

        $manager->flush();
    }
}