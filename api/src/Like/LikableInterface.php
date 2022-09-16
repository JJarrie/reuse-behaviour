<?php

namespace App\Like;

interface LikableInterface
{
    public function like(string $userIri): void;

    public function unlike(string $userIri): void;

    public function isLiked(string $userIri): bool;
}
