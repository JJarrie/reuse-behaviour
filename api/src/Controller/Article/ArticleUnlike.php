<?php

namespace App\Controller\Article;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class ArticleUnlike extends AbstractController
{
    public function __construct(
        private readonly IriConverterInterface  $iriConverter,
        private readonly Security               $security,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(Article $article): Response
    {
        $user = $this->security->getToken()->getUser();
        $userIri = $this->iriConverter->getIriFromResource($user);

        $article->unlike($userIri);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
