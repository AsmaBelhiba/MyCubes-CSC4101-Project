<?php

namespace App\DataFixtures;

use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    private function membersGenerator(): \Generator
    {
        yield ['olivier@localhost', '123456', 'ROLE_ADMIN'];
        yield ['slash@localhost', '123456', 'ROLE_USER'];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->membersGenerator() as [$email, $plainPassword, $role]) {

            $member = new Member();

            $member->setEmail($email);

            $member->setPassword(
                $this->hasher->hashPassword($member, $plainPassword)
            );

            $member->setRoles([$role]);

            $manager->persist($member);

            $this->addReference($email, $member);
        }

        $manager->flush();
    }
}
