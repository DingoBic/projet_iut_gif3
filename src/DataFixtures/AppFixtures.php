<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// use Faker\Factory;

class AppFixtures extends Fixture
{

    private Generator $faker;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 15 ; $i ++) { 
            $user = new User();
            $user->setEmail($this->faker->email);
            // $hashPassword = $this->hasher->hashPassword(
            //     $user,
            //     'password'
            // );
            // $user->setPassword($hashPassword);
            $user->setPlainPassword('password');
            $user->setUsername($this->faker->firstName());
            $user->setTelephone($this->faker->phoneNumber());
            $user->setRoles($i%2 == 0? ['ROLE_USER'] : ['ROLE_ENTREPRISE']);
            $user->setAdresse($this->faker->address());

            $manager->persist($user);
        }

        $manager->flush();
    }
}
