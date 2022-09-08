<?php

namespace App\Controller\Comment;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class CommentIsLiked extends AbstractController
{
    public function __construct(private readonly IriConverterInterface $iriConverter,
                                private readonly Security              $security)
    {
    }

    public function __invoke(Comment $comment): bool
    {
        $user = $this->security->getToken()->getUser();
        $userIri = $this->iriConverter->getIriFromResource($user);

        return $comment->isLiked($userIri);
    }
}
