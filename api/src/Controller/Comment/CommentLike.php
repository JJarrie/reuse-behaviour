<?php

namespace App\Controller\Comment;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class CommentLike extends AbstractController
{
    public function __construct(private readonly IriConverterInterface $iriConverter,
                                private readonly Security              $security)
    {
    }

    public function __invoke(Comment $comment): Comment
    {
        $user = $this->security->getToken()->getUser();
        $userIri = $this->iriConverter->getIriFromResource($user);

        $comment->like($userIri);

        return $comment;
    }
}
