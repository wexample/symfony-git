<?php

namespace Wexample\SymfonyGit\Service;

use DateTimeImmutable;
use Wexample\PhpGit\Class\GitRepositoryReader;
use Wexample\SymfonyGit\Entity\Commit;

/**
 * The history of a repository as entities, a page at a time: what a Doctrine
 * repository is to a stored entity, read from `.git` (GitRepositoryReader).
 */
class CommitProvider
{
    public function __construct(
        private readonly GitRepositoryReader $git,
    ) {
    }

    /**
     * `$length` commits from the `$offset`-th on, newest first and each after
     * its children; given a `$path`, the commits that touched it. Null when the
     * repository cannot be read.
     *
     * @return list<Commit>|null
     */
    public function page(
        string $name,
        string $repository,
        int $offset,
        int $length,
        ?string $path = null,
    ): ?array {
        $log = $this->git->log($repository, $length, $path, $offset);

        if (null === $log) {
            return null;
        }

        return array_map(
            fn (array $line): Commit => (new Commit(
                $name,
                $line['hash'],
                $line['subject'],
                $line['author'],
                new DateTimeImmutable($line['date']),
            ))
                ->setParents($line['parents'])
                ->setRefs($line['refs']),
            $log
        );
    }

    /**
     * How many commits `page()` reads through: the whole history, or the
     * commits touching `$path`. Null when the repository cannot be read.
     */
    public function count(
        string $repository,
        ?string $path = null,
    ): ?int {
        return $this->git->count($repository, $path);
    }
}
