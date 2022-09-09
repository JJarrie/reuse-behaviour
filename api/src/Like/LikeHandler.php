<?php

namespace App\Like;

use ApiPlatform\Api\IriConverterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;

class LikeHandler
{
    public function __construct(private readonly IriConverterInterface $iriConverter)
    {
    }

    public function like(LikableInterface $likable, UserInterface $user): LikableInterface
    {
        $likable->like($this->iriConverter->getIriFromResource($user));

        return $likable;
    }

    public function unlike(LikableInterface $likable, UserInterface $user): LikableInterface
    {
        $likable->unlike($this->iriConverter->getIriFromResource($user));

        return $likable;
    }

    public function isLiked(LikableInterface $likable, UserInterface $user): bool
    {
        return $likable->isLiked($this->iriConverter->getIriFromResource($user));
    }
}
