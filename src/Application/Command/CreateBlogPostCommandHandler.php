<?php

namespace Blog\Application\Command;

use Blog\Domain\Command\CommandHandlerInterface;
use Blog\Domain\Event\BlogPostCreated;
use Blog\Domain\Event\EventBusInterface;
use Blog\Domain\Model\BlogPost;
use Blog\Domain\Repository\PostRepositoryInterface;
use Ramsey\Uuid\Uuid;

final readonly class CreateBlogPostCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PostRepositoryInterface $repository,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateBlogPostCommand $command): BlogPost
    {
        $this->repository->save($blogPost = new BlogPost(
            id: null,
            uuid: $uuid = Uuid::uuid4(),
            title: $command->title,
            content: $command->content,
            summary: $command->summary,
            author: $command->author,
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable(),
        ));

        $this->eventBus->publish(new BlogPostCreated($uuid));

        return $blogPost;
    }
}
