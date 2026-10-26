<?php

namespace Wexample\SymfonyGit\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wexample\SymfonyGit\Interface\GitActivityProviderInterface;
use Wexample\SymfonyHelpers\DependencyInjection\AbstractWexampleSymfonyExtension;

class WexampleSymfonyGitExtension extends AbstractWexampleSymfonyExtension
{
    /** The repositories the API serves, absolute paths by name. */
    final public const string PARAMETER_REPOSITORIES = 'wexample_symfony_git.repositories';

    public function load(
        array $configs,
        ContainerBuilder $container
    ): void {
        // The application's own, when it declared some: none by default, so
        // the API reads nothing it was not told to.
        $container->setParameter(
            self::PARAMETER_REPOSITORIES,
            $container->hasParameter(self::PARAMETER_REPOSITORIES)
                ? $container->getParameter(self::PARAMETER_REPOSITORIES)
                : []
        );

        // Implemented by the bundles knowing repositories (symfony-remote-git):
        // the tag follows the interface wherever it is implemented.
        $container
            ->registerForAutoconfiguration(GitActivityProviderInterface::class)
            ->addTag(GitActivityProviderInterface::TAG);

        $this->loadConfig(
            __DIR__,
            $container
        );
    }
}
