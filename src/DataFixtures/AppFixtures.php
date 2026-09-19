<?php

namespace App\DataFixtures;

use App\Entity\Cube;
use App\Entity\CubeCollection;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Generates initialization data for members :
     *  [email, plain text password]
     * @return \Generator
     */
    private function membersGenerator()
    {
        yield ['olivier@localhost', '123456'];
        yield ['slash@localhost', '123456'];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->membersGenerator() as [$email, $plainPassword]) {
            $user = new Member();

            $password = $this->hasher->hashPassword($user, $plainPassword);

            $user->setEmail($email);
            $user->setPassword($password);

            $collection = new CubeCollection();
            $collection->setDescription('Collection de ' . $email);

            $user->setCubeCollection($collection);

            $cube1 = new Cube();
            $cube1->setDescription('Cube Rubik 3x3');
            $collection->addCube($cube1);

            $cube2 = new Cube();
            $cube2->setDescription('Cube Rubik 4x4');
            $collection->addCube($cube2);

            $manager->persist($user);
            $manager->persist($collection);
        }

        $manager->flush();
    }
}
