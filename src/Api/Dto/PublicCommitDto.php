<?php

namespace Wexample\SymfonyGit\Api\Dto;

use DateTimeInterface;
use Wexample\SymfonyApi\Api\Dto\AbstractEntityDto;
use Wexample\SymfonyGit\Entity\Commit;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;

/**
 * A commit as it goes on the wire: what a history line shows, and the hashes
 * a graph is drawn from.
 */
class PublicCommitDto extends AbstractEntityDto
{
    public string $repository;

    public string $hash;

    /** @var list<string> */
    public array $parents;

    public string $subject;

    public string $author;

    /** ISO 8601. */
    public string $date;

    /** @var list<string> */
    public array $refs;

    /**
     * @param Commit $entity
     */
    public static function fromEntity(AbstractEntity $entity): self
    {
        $dto = parent::fromEntity($entity);

        $dto->repository = $entity->getRepository();
        $dto->hash = $entity->getHash();
        $dto->parents = $entity->getParents();
        $dto->subject = $entity->getSubject();
        $dto->author = $entity->getAuthor();
        $dto->date = $entity->getDate()->format(DateTimeInterface::ATOM);
        $dto->refs = $entity->getRefs();

        return $dto;
    }
}
