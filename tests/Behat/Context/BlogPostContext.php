<?php

declare(strict_types=1);

namespace Blog\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Blog\Infrastructure\Foundry\Factory\BlogPostFactory;
use Blog\Tests\Behat\Storage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ramsey\Uuid\Uuid;

final class BlogPostContext implements Context
{
    public function __construct(
        EntityManagerInterface $em,
        private Storage $storage,
    ) {
        foreach ($em->getMetadataFactory()->getAllMetadata() as $metadata) {
            if (!preg_match('/^Blog\\\Domain\\\\\w+\\\Model/', (string) $metadata->namespace)) {
                continue;
            }

            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        }
    }

    /**
     * @Given there are :number posts
     */
    public function createBlogPosts(int $number): void
    {
        BlogPostFactory::createMany($number);
    }

    /**
     * @Given there is a post with uuid :uuid
     */
    public function createBlogPost(string $uuid): void
    {
        $post = BlogPostFactory::createOne([
            'uuid' => Uuid::fromString($uuid),
        ]);
        $this->storage->setBlogPost($post);
    }

    /**
     * @Given that post has title :title
     */
    public function updateBlogPost(string $title): void
    {
        $post = $this->storage->getBlogPost();
        $post->forceSet('title', $title);
    }
}
