<?php

namespace Wexample\SymfonyGit\Entity;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Wexample\Pseudocode\Attribute\PseudocodeExport;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;

/**
 * One commit of a repository, as its history lists it.
 *
 * An entity without an ORM mapping: a commit is in `.git`, never in a table,
 * and is read there (CommitProvider). It is an entity for what being one gives
 * on the way out — the pseudocode export, the generated `Commit.ts` and its
 * repository, and a history being a collection of entities like any other.
 * Doctrine skips a class carrying no `#[ORM\Entity]`.
 */
#[PseudocodeExport(inherited: true)]
class Commit extends AbstractEntity
{
    /**
     * Fixed namespace the identity is hashed under, so the same commit read
     * twice — on another page, after a reload — is the same entity twice.
     */
    public const string ID_NAMESPACE = '0ba25f15-e5f7-4510-be87-2e32ab3556f6';

    /** The repository it was read from, by the name the API knows it under. */
    protected string $repository;

    /** The full hash. */
    protected string $hash;

    /**
     * The full hashes of its parents: none for the first commit, two for a
     * merge. In a path's history, the previous commits that touched the path.
     *
     * @var list<string>
     */
    protected array $parents = [];

    /** The first line of its message. */
    protected string $subject;

    protected string $author;

    protected DateTimeImmutable $date;

    /**
     * The refs pointing at it, as git names them: `HEAD -> main`,
     * `origin/main`, `tag: v1.0`.
     *
     * @var list<string>
     */
    protected array $refs = [];

    public function __construct(
        string $repository,
        string $hash,
        string $subject,
        string $author,
        DateTimeImmutable $date,
    ) {
        parent::__construct();

        $this->repository = $repository;
        $this->hash = $hash;
        $this->subject = $subject;
        $this->author = $author;
        $this->date = $date;

        $this->setId(self::idFor($repository, $hash));
    }

    /** The identity of a commit, which is its repository and its hash. */
    public static function idFor(
        string $repository,
        string $hash,
    ): Uuid {
        return Uuid::v5(
            Uuid::fromString(self::ID_NAMESPACE),
            $repository."\0".$hash
        );
    }

    public function getRepository(): string
    {
        return $this->repository;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return list<string>
     */
    public function getParents(): array
    {
        return $this->parents;
    }

    /**
     * @param list<string> $parents
     */
    public function setParents(array $parents): self
    {
        $this->parents = $parents;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return list<string>
     */
    public function getRefs(): array
    {
        return $this->refs;
    }

    /**
     * @param list<string> $refs
     */
    public function setRefs(array $refs): self
    {
        $this->refs = $refs;

        return $this;
    }
}
