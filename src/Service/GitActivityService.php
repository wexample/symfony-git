<?php

namespace Wexample\SymfonyGit\Service;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Wexample\SymfonyGit\Class\GitActivity;
use Wexample\SymfonyGit\Interface\GitActivityProviderInterface;

/**
 * The work of every GitActivityProviderInterface summed up: their
 * repositories counted once each, their commits added day by day.
 */
class GitActivityService
{
    /** A year of days, today included: what a calendar shows. */
    public const int DAYS = 365;

    /**
     * @param iterable<GitActivityProviderInterface> $providers
     */
    public function __construct(
        #[AutowireIterator(GitActivityProviderInterface::TAG)]
        private readonly iterable $providers = [],
    ) {
    }

    /**
     * The last DAYS days up to today.
     */
    public function getActivity(): GitActivity
    {
        $to = new DateTimeImmutable('tomorrow');
        $from = $to->modify('-'.self::DAYS.' days');
        $names = [];
        $days = [];

        foreach (new DatePeriod($from, new DateInterval('P1D'), $to) as $day) {
            $days[$day->format('Y-m-d')] = 0;
        }

        foreach ($this->providers as $provider) {
            $names += array_fill_keys($provider->getRepositoryNames(), true);

            foreach ($provider->getDailyCommits($from, $to) as $day => $commits) {
                if (isset($days[$day])) {
                    $days[$day] += $commits;
                }
            }
        }

        return new GitActivity(count($names), $days);
    }
}
