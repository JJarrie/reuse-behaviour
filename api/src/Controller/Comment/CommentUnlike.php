<?php

namespace App\Controller\Comment;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class CommentUnlike extends AbstractController
{
    public function __construct(
        private readonly IriConverterInterface $iriConverter,
        private readonly Security              $security,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(Comment $comment): Response
    {
        $user = $this->security->getToken()->getUser();
        $userIri = $this->iriConverter->getIriFromResource($user);

        $comment->unlike($userIri);

        $this->entityManager>flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
