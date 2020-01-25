<?php
namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\User\Model\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = User::create('admin', 'admin', 'emailadmin', 'admin');
        $manager->persist($admin);

        for ($i = 0; $i < 20; $i++) {
            $user = User::create('username'.$i, 'password'.$i, 'email'.$i);
            $manager->persist($user);
        }

        $manager->flush();
    }
}