<?php

namespace App\Controller\Comment;

use App\Entity\Comment;
use App\Like\LikeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class IsLikedComment extends AbstractController
{
    public function __construct(private readonly LikeHandler $likeHandler,
                                private readonly Security    $security)
    {
    }

    public function __invoke(Comment $data): bool
    {
        return $this->likeHandler->isLiked($data, $this->security->getToken()->getUser());
    }
}
