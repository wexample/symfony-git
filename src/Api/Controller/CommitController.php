<?php

namespace Wexample\SymfonyGit\Api\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyApi\Api\Attribute\ApiResponseData;
use Wexample\SymfonyApi\Api\Attribute\QueryOption\LengthQueryOption;
use Wexample\SymfonyApi\Api\Attribute\QueryOption\PageQueryOption;
use Wexample\SymfonyApi\Api\Attribute\QueryOption\StringQueryOption;
use Wexample\SymfonyApi\Api\Class\ApiResponse;
use Wexample\SymfonyApi\Api\Controller\AbstractApiController;
use Wexample\SymfonyGit\Api\Dto\PublicCommitDto;
use Wexample\SymfonyGit\Api\Normalizer\Entity\Commit\DefaultCommitNormalizer;
use Wexample\SymfonyGit\Interface\GitRepositoryResolverInterface;
use Wexample\SymfonyGit\Service\CommitProvider;
use Wexample\SymfonyHelpers\Controller\AbstractController;

/**
 * A repository's history, as the collection of one kind of entity: the
 * `CommitRepository.ts` generated from the same class reads it page after page.
 */
#[Route(path: '_api/commit/', name: 'api_commit_')]
class CommitController extends AbstractApiController
{
    final public const string ROUTE_LIST = 'list';

    final public const string QUERY_OPTION_REPOSITORY = 'repository';

    final public const string QUERY_OPTION_PATH = 'path';

    /** A history is read by screens of lines, not by the ten an entity list starts at. */
    final public const int LIST_PAGE_LENGTH = 100;

    #[Route(path: 'list', name: self::ROUTE_LIST, methods: AbstractController::ROUTE_OPTIONS_METHOD_ONLY_GET, options: AbstractController::ROUTE_OPTIONS_ONLY_EXPOSE)]
    #[StringQueryOption(key: self::QUERY_OPTION_REPOSITORY, default: '')]
    // A path in the repository: its own history rather than the whole one.
    #[StringQueryOption(key: self::QUERY_OPTION_PATH, default: '')]
    #[PageQueryOption]
    #[LengthQueryOption(default: self::LIST_PAGE_LENGTH)]
    #[ApiResponseData(PublicCommitDto::class, paginated: true)]
    public function list(
        Request $request,
        GitRepositoryResolverInterface $repositories,
        CommitProvider $provider,
        DefaultCommitNormalizer $normalizer,
    ): ApiResponse {
        $name = (string) self::getQueryOptionValue($request, self::QUERY_OPTION_REPOSITORY, '');
        $repository = '' === $name ? null : $repositories->path($name);

        if (null === $repository) {
            throw $this->createNotFoundException('Unknown repository: '.$name);
        }

        $path = (string) self::getQueryOptionValue($request, self::QUERY_OPTION_PATH, '');
        $path = '' === $path ? null : $path;

        $total = $provider->count($repository, $path);

        if (null === $total) {
            throw $this->createNotFoundException('Unreadable repository: '.$name);
        }

        // Counted, so that a negative page — the oldest first — and the pager
        // both know where the history ends.
        $pagination = self::getQueryOptionPagination($request, $total);

        // No length is no limit: the whole history, at once.
        $commits = $provider->page(
            $name,
            $repository,
            $pagination->getOffset(),
            $pagination->length ?? max(1, $total),
            $path
        );

        return self::apiResponsePaginated(
            pagination: $pagination,
            items: $normalizer->normalizeCollection($commits ?? [])
        );
    }
}
