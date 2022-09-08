<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Article\ArticleIsLiked;
use App\Controller\Article\ArticleLike;
use App\Controller\Article\ArticleUnlike;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(mercure: true)]
#[Get]
#[GetCollection]
#[Post]
#[Post(uriTemplate: '/articles/{id}/like', controller: ArticleLike::class)]
#[Post(uriTemplate: '/articles/{id}/unlike', controller: ArticleUnlike::class)]
#[Get(uriTemplate: '/articles/{id}/is_liked', controller: ArticleIsLiked::class)]
#[ORM\Entity]
class Article
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public string $title = '';

    #[ORM\Column]
    #[Assert\NotBlank]
    public string $content = '';

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Comment::class, orphanRemoval: true)]
    public Collection $comments;

    #[ORM\Column(type: Types::ARRAY)]
    public array $likes = [];

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

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

    public function unlike(string $userIri): void {
        $userKey = array_search($userIri, $this->likes, true);

        if (false !== $userKey) {
            unset($this->likes[$userKey]);
        }
    }

    public function isLiked(string $userIri): bool {
        return in_array($userIri, $this->likes);
    }
}
