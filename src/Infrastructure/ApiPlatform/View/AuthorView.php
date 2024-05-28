<?php

namespace Blog\Infrastructure\ApiPlatform\View;

use Symfony\Component\Serializer\Annotation\Groups;

class AuthorView
{
    public function __construct(
        #[Groups(['post:item', 'post:list'])]
        public string $name,
    ) {
    }
}
