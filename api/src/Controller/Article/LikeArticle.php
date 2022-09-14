<?php

namespace App\Controller\Article;

use App\Entity\Article;
use App\Like\LikableInterface;
use App\Like\LikeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class LikeArticle extends AbstractController
{
    public function __construct(
        private readonly LikeHandler $likeHandler,
        private readonly Security    $security,
    )
    {
    }

    public function __invoke(Article $data): LikableInterface
    {
        return $this->likeHandler->like($data, $this->security->getToken()->getUser());
    }
}
