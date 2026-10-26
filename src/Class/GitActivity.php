<?php

namespace Wexample\SymfonyGit\Class;

/**
 * The work done in the repositories a site knows, over a period: how many
 * there are, and the commits of each day.
 */
final readonly class GitActivity
{
    /**
     * @param array<string, int> $days commits by `Y-m-d`, every day of the
     *                                  period, a quiet one at zero
     */
    public function __construct(
        public int $repositories,
        public array $days,
    ) {
    }

    public function getCommits(): int
    {
        return array_sum($this->days);
    }
}
