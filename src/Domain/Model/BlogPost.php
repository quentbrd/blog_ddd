<?php

namespace Blog\Domain\Model;

use Ramsey\Uuid\UuidInterface;

final class BlogPost
{
    public function __construct(
        private ?int $id,
        private UuidInterface $uuid,
        private string $title,
        private string $content,
        private string $summary,
        private Author $author,
        private \DateTimeInterface $createdAt,
        private \DateTimeInterface $updatedAt,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }
}
