<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(mercure: true)]
#[ORM\Entity]
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

    #[ORM\ManyToOne(targetEntity: Comment::class, inversedBy: 'comments')]
    public ?Comment $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Comment::class, orphanRemoval: true)]
    public Collection $comments;

    public function getId(): ?int
    {
        return $this->id;
    }
}
