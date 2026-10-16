<?php

namespace Wexample\SymfonyGit\Api\Normalizer\Entity\Commit;

use ArrayObject;
use Wexample\SymfonyGit\Api\Dto\PublicCommitDto;
use Wexample\SymfonyGit\Entity\Commit;
use Wexample\SymfonyGit\Entity\Traits\Manipulator\CommitManipulatorTrait;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;
use Wexample\SymfonyHelpers\Interface\NormalizableDataInterface;
use Wexample\SymfonyHelpers\Normalizer\AbstractEntityNormalizer;

class DefaultCommitNormalizer extends AbstractEntityNormalizer
{
    use CommitManipulatorTrait;

    public function normalizeEntity(
        Commit|AbstractEntity $entity,
        ?string $format = null,
        array $context = []
    ): array|string|int|float|bool|ArrayObject|NormalizableDataInterface|null {
        return PublicCommitDto::fromEntity($entity);
    }
}
