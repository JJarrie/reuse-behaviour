<?php

namespace App\Controller\Article;

use App\Entity\Article;
use App\Like\LikableInterface;
use App\Like\LikeHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class UnlikeArticle extends AbstractController
{
    public function __construct(
        private readonly LikeHandler            $likeHandler,
        private readonly Security               $security,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(Article $data): Response
    {
        $this->likeHandler->unlike($data, $this->security->getToken()->getUser());
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
