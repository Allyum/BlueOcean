<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Token;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TokenFixture extends Fixture implements DependentFixtureInterface

{
    public function load(ObjectManager $manager): void
    {
        $token = new Token();
        $token->setUser($this->getReference('BlueOcean@plsDontSueMe.com', User::class));
        $token->setValue('saoihzjeibfiusef');
        $token->setExpiresAt(new \DateTime());
        $manager->persist($token);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
