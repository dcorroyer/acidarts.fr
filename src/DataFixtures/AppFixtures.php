<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $hash = $this->encoder->hashPassword($user, 'password');

        $user->setUsername('admin')
            ->setEmail('dylan.corroyer@wanadoo.fr')
            ->setPassword($hash);

        $manager->persist($user);
        $manager->flush();
    }
}
