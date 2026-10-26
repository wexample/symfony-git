## The work done in the repositories

`GitActivityService::getActivity()` sums up the repositories a site knows over the last year (`DAYS`, today included): `GitActivity` holds how many there are (`repositories`) and the commits of each day (`days`, a quiet one at zero; `getCommits()` their sum).

It knows them from the services implementing `GitActivityProviderInterface` — autoconfigured, the tag follows the interface —, each telling its repositories' names and the commits of each day: a repository two providers know is counted once, their commits are added. symfony-remote-git is one, for GitHub and GitLab accounts.

The chart `git-activity` (symfony-charts) serves those days as points, one a day, for a calendar: `chart('calendar', {}, { source: 'git-activity' })` (symfony-charts-ds).
