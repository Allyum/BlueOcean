<?php

namespace App\Business;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class TokenBusiness
{
    public function __construct(
        private MathBusiness $mathBusiness,
    ){}
    public function generateTokenValue(int $size = 32): string
    {
        return bin2hex(random_bytes(floor($this->mathBusiness->div($size, 2))));
    }

    public function createToken(User $user): Token
    {
        $token = (new Token())
            ->setUser($user)
            ->setValue($this->generateTokenValue())
            ->setExpiresAt(
                (new \DateTime())
                    ->add(new \DateInterval('PT15M'))
            );

        return $token;
    }
}
