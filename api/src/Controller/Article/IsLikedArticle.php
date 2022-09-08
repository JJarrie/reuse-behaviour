<?php

namespace App\Controller\Article;

use App\Entity\Article;
use App\Like\LikeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class IsLikedArticle extends AbstractController
{
    public function __construct(private readonly LikeHandler $likeHandler,
                                private readonly Security    $security)
    {
    }

    public function __invoke(Article $data): bool
    {
        return $this->likeHandler->isLiked($data, $this->security->getToken()->getUser());
    }
}
