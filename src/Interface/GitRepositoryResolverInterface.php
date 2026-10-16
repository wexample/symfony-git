<?php

namespace Wexample\SymfonyGit\Interface;

/**
 * Where a repository the API is asked about stands on disk.
 *
 * The API is asked by name and never by path: a client naming a path would be
 * reading any repository the process can reach. What is not answered here does
 * not exist for the API — the resolver is also where an application says who
 * may read what.
 */
interface GitRepositoryResolverInterface
{
    /**
     * The absolute path of the repository named `$repository`, or null when
     * there is none by that name, or none this request may read.
     */
    public function path(string $repository): ?string;
}
