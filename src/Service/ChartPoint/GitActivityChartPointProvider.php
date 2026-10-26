<?php

namespace Wexample\SymfonyGit\Service\ChartPoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wexample\SymfonyCharts\Helper\ChartPointHelper;
use Wexample\SymfonyCharts\Interface\ChartPointProviderInterface;
use Wexample\SymfonyGit\Service\GitActivityService;

/**
 * The commits of each day of the last year, for a calendar: one point a day,
 * a quiet one at zero.
 */
class GitActivityChartPointProvider implements ChartPointProviderInterface
{
    public function __construct(
        private readonly GitActivityService $activity,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public static function getChartName(): string
    {
        return 'git-activity';
    }

    public function getPoints(Request $request): iterable
    {
        $days = $this->activity->getActivity()->days;

        return ChartPointHelper::series(
            self::getChartName(),
            'commits',
            array_keys($days),
            $days,
            $this->translator->trans('WexampleSymfonyGitBundle.common.git::commits'),
        );
    }
}
