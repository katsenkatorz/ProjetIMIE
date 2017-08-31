<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();

        $admin
            ->setUsername('admin')
            ->setUsernameCanonical('admin')
            ->setPlainPassword('P@ssword')
            ->setEmail('default.name@domain.tld')
            ->setEmailCanonical('default.name@domain.tld')
            ->setEnabled(true)
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $manager->persist($admin);
        $manager->flush();
    }
}