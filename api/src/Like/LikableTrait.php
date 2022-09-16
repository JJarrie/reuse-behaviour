<?php

namespace App\Like;

trait LikableTrait
{
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
