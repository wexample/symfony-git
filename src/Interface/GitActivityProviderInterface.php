<?php

namespace Wexample\SymfonyGit\Interface;

use DateTimeImmutable;

/**
 * What a bundle implements to tell the work done in the repositories it
 * knows — on a host (symfony-remote-git), on disk. Autoconfigured:
 * GitActivityService sums every one.
 */
interface GitActivityProviderInterface
{
    public const string TAG = 'wexample_symfony_git.activity_provider';

    /**
     * The names of its repositories: a repository two providers know is
     * counted once.
     *
     * @return list<string>
     */
    public function getRepositoryNames(): array;

    /**
     * The commits of each day from `from` to `to` excluded, by `Y-m-d`; a
     * day without any may be left out.
     *
     * @return array<string, int>
     */
    public function getDailyCommits(DateTimeImmutable $from, DateTimeImmutable $to): array;
}
