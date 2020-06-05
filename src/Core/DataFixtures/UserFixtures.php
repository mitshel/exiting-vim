<?php

namespace Core\DataFixtures;

use Core\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('test');
        $user->setPlainPassword('test');

        $manager->persist($user);
        $manager->flush();
    }
}