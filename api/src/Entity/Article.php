<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\IsLiked;
use App\Controller\Like;
use App\Controller\Unlike;
use App\Like\DoctrineLikeFieldTrait;
use App\Like\LikableInterface;
use App\Like\LikableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(mercure: true)]
#[Get]
#[GetCollection]
#[Post]
#[ORM\Entity]
#[Post(uriTemplate: '/articles/{id}/like', controller: Like::class)]
#[Post(uriTemplate: '/articles/{id}/unlike', controller: Unlike::class)]
#[Get(uriTemplate: '/articles/{id}/is_liked', controller: IsLiked::class)]
class Article implements LikableInterface
{
    use DoctrineLikeFieldTrait, LikableTrait;

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

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
