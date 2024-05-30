<?php

namespace Blog\Domain\Event;

class BlogPostCreated extends AbstractEntityEvent
{
    public static function eventName(): string
    {
        return 'blog.post.created';
    }
}
