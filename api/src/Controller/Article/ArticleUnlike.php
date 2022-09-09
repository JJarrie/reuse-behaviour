<?php

namespace App\Controller\Article;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class ArticleUnlike extends AbstractController
{
    public function __construct(
        private readonly IriConverterInterface $iriConverter,
        private readonly Security              $security,
    )
    {
    }

    public function __invoke(Article $article): Article
    {
        $user = $this->security->getToken()->getUser();
        $userIri = $this->iriConverter->getIriFromResource($user);

        $article->unlike($userIri);

        return $article;
    }
}
