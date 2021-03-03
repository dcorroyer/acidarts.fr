<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($u = 0; $u < 1; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setUsername('admin')
                ->setEmail('dylan.corroyer@wanadoo.fr')
                ->setPassword($hash);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
