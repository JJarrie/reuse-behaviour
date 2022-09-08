<?php

namespace App\Like;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

trait DoctrineLikeFieldTrait
{
    #[ORM\Column(type: Types::ARRAY)]
    public array $likes = [];
}
