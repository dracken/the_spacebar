<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i){
        $user = new User();
        $user->setEmail(sprintf('spacebar%d@example.com', $i));
        $user->setFirstname($this->faker->FirstName);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'engage'
        ));
        if ($this->faker->boolean) {
            $user->setTwitterUsername($this->faker->userName);
        }

        return $user;
    });

        $this->createMany(3, 'admin_users', function($i){
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com', $i));
            $user->setFirstname($this->faker->FirstName);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));

            $user->setRoles(['ROLE_ADMIN']);
            if ($this->faker->boolean) {
                $user->setTwitterUsername($this->faker->userName);
            }

            return $user;
        });

        $manager->flush();
    }
}
