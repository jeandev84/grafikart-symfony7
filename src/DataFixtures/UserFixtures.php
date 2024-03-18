<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setRoles(['ROLE_ADMIN'])
             ->setEmail('admin@doe.fr')
             ->setUsername('admin')
             ->setIsVerified(true)
             ->setPassword($this->hasher->hashPassword($user, '1234'))
             ->setApiToken('admin_token') // just for example
        ;
        $manager->persist($user);

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setRoles([])
                 ->setEmail("user{$i}@doe.fr")
                 ->setUsername("user{$i}")
                 ->setIsVerified(true)
                 ->setPassword($this->hasher->hashPassword($user, '1234'))
                 ->setApiToken("user{$i}");
            $manager->persist($user);
        }

        $manager->flush();
    }
}
