<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Comment\CommentIsLiked;
use App\Controller\Comment\CommentLike;
use App\Controller\Comment\CommentUnlike;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(mercure: true)]
#[ORM\Entity]
#[Get]
#[GetCollection]
#[Post]
#[Post(uriTemplate: '/comments/{id}/like', controller: CommentLike::class)]
#[Post(uriTemplate: '/comments/{id}/unlike', controller: CommentUnlike::class)]
#[Get(uriTemplate: '/comments/{id}/is_liked', controller: CommentIsLiked::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public string $content = '';

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'comments')]
    public Article $article;

    #[ORM\Column(type: Types::ARRAY)]
    public array $likes = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function like(string $userIri): void
    {
        if (!$this->isLiked($userIri)) {
            $this->likes[] = $userIri;
        }
    }

    public function unlike(string $userIri): void
    {
        $userKey = array_search($userIri, $this->likes, true);

        if (false !== $userKey) {
            unset($this->likes[$userKey]);
        }
    }

    public function isLiked(string $userIri): bool
    {
        return in_array($userIri, $this->likes);
    }
}
