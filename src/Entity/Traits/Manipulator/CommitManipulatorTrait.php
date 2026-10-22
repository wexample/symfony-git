<?php

namespace Wexample\SymfonyGit\Entity\Traits\Manipulator;

use Wexample\SymfonyGit\Entity\Commit;
use Wexample\SymfonyHelpers\Entity\Traits\Manipulator\EntityManipulatorTrait;

trait CommitManipulatorTrait
{
    use EntityManipulatorTrait;

    public static function getEntityClassName(): string
    {
        return Commit::class;
    }
}
