<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Like\DoctrineLikeFieldTrait;
use App\Like\LikableInterface;
use App\Like\LikableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(mercure: true)]
#[ORM\Entity]
#[Get]
#[GetCollection]
#[Post]
class Comment implements LikableInterface
{
    use DoctrineLikeFieldTrait, LikableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public string $content = '';

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'comments')]
    public Article $article;

    public function getId(): ?int
    {
        return $this->id;
    }
}
