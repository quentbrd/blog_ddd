<?php

namespace Blog\Infrastructure\ApiPlatform\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Blog\Application\Command\CreateBlogPostCommand;
use Blog\Application\Query\FindAuthorByUuidQuery;
use Blog\Domain\Command\CommandBusInterface;
use Blog\Domain\Exception\EntityCreationException;
use Blog\Domain\Exception\RelatedEntityNotFoundException;
use Blog\Domain\Model\BlogPost;
use Blog\Domain\Query\QueryBusInterface;
use Blog\Infrastructure\ApiPlatform\Input\BlogPostCreationInput;
use Blog\Infrastructure\ApiPlatform\Resource\BlogPostResource;
use Blog\Infrastructure\ApiPlatform\Resource\BlogPostResourceFactory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @implements ProcessorInterface<BlogPostCreationInput, BlogPostResource>
 */
class BlogPostCreationProcessor implements ProcessorInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private BlogPostResourceFactory $resourceFactory,
    ) {
    }

    /**
     * @param BlogPostCreationInput $data
     * @param array<string, mixed>  $uriVariables
     * @param array<string, mixed>  $context
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): BlogPostResource
    {
        if ($this->validator->validate($data)->count() > 0) {
            throw new EntityCreationException(BlogPost::class);
        }

        $author = $this->queryBus->ask(new FindAuthorByUuidQuery(Uuid::fromString($data->authorUuid)));
        if (null === $author) {
            throw new RelatedEntityNotFoundException('Author', 'BlogPost', $data->authorUuid, 'uuid');
        }

        $blogPost = $this->commandBus->dispatch(new CreateBlogPostCommand(
            $data->title,
            $data->content,
            $data->summary,
            $author,
        ));

        return $this->resourceFactory->fromModel($blogPost);
    }
}
