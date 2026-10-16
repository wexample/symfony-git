<?php

namespace Wexample\SymfonyGit\Service;

use Wexample\SymfonyGit\Interface\GitRepositoryResolverInterface;

/**
 * The repositories the application declared, by name
 * (`wexample_symfony_git.repositories`). An application finding its
 * repositories at runtime — one per project it manages, say — aliases
 * GitRepositoryResolverInterface to its own resolver instead.
 */
class ConfiguredGitRepositoryResolver implements GitRepositoryResolverInterface
{
    /**
     * @param array<string, string> $repositories absolute paths, by name
     */
    public function __construct(
        private readonly array $repositories,
    ) {
    }

    public function path(string $repository): ?string
    {
        return $this->repositories[$repository] ?? null;
    }
}
