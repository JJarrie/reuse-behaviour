<?php

namespace App\Controller;

use App\Entity\Article;
use App\Like\LikableInterface;
use App\Like\LikeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class IsLiked extends AbstractController
{
    public function __construct(private readonly LikeHandler $likeHandler,
                                private readonly Security    $security)
    {
    }

    public function __invoke(LikableInterface $data): bool
    {
        return $this->likeHandler->isLiked($data, $this->security->getToken()->getUser());
    }
}
