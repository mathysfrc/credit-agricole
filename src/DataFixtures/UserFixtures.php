<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasser;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasser = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasser->hashPassword($user, 'adminpassword');
        $user->setPassword($hashedPassword);
        $user->setLastName('ADMIN');
        $user->setFirstName('ISF');
        $user->setFonction('ADMIN');
        $user->setCompany('ISF');
        $this->addReference('user_admin', $user);
        $manager->persist($user);

        $manager->flush();
    }
}
