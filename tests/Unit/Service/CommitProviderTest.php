<?php

namespace Wexample\SymfonyGit\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Wexample\PhpGit\Class\GitRepositoryReader;
use Wexample\SymfonyGit\Entity\Commit;
use Wexample\SymfonyGit\Service\CommitProvider;

class CommitProviderTest extends TestCase
{
    private string $repository;

    protected function setUp(): void
    {
        $this->repository = sys_get_temp_dir().'/symfony-git-provider-'.bin2hex(random_bytes(4));
        mkdir($this->repository);
        $this->git(['init', '--quiet', '--initial-branch=main']);

        foreach (['first', 'second', 'third'] as $subject) {
            file_put_contents($this->repository.'/file.txt', $subject."\n", FILE_APPEND);
            $this->git(['add', 'file.txt']);
            $this->git(['-c', 'user.name=Test', '-c', 'user.email=test@example.com', 'commit', '--quiet', '-m', $subject]);
        }
    }

    protected function tearDown(): void
    {
        (new Process(['rm', '-rf', $this->repository]))->run();
    }

    public function testReadsAPageOfCommitsAsEntities(): void
    {
        $provider = new CommitProvider(new GitRepositoryReader());
        $page = $provider->page('demo', $this->repository, 1, 2);

        $this->assertSame(3, $provider->count($this->repository));
        $this->assertSame(['second', 'first'], array_map(fn (Commit $commit) => $commit->getSubject(), $page));
        $this->assertSame([$page[1]->getHash()], $page[0]->getParents());
        $this->assertSame([], $page[1]->getParents());
        $this->assertSame('Test', $page[0]->getAuthor());
    }

    public function testGivesACommitTheSameIdAtEveryRead(): void
    {
        $provider = new CommitProvider(new GitRepositoryReader());
        $first = $provider->page('demo', $this->repository, 0, 1)[0];
        $again = $provider->page('demo', $this->repository, 0, 1)[0];

        $this->assertEquals($first->getId(), $again->getId());
        $this->assertEquals(Commit::idFor('demo', $first->getHash()), $first->getId());
        $this->assertNotEquals(Commit::idFor('other', $first->getHash()), $first->getId());
    }

    private function git(array $arguments): void
    {
        (new Process(['git', '-C', $this->repository, ...$arguments]))->mustRun();
    }
}
