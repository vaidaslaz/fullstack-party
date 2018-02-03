<?php

/*
 * (c) Vaidas Lažauskas <vaidas@notrix.lt>
 */

namespace App\Twig;

use App\Exception\IssueUrlParseException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * App\Twig\IssueExtension
 */
class IssueExtension extends AbstractExtension
{
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('issueNumber', [$this, 'getIssueNumber']),
            new TwigFilter('issuePath', [$this, 'getIssuePath']),
        ];
    }

    /**
     * @param string $url
     *
     * @return int
     *
     * @throws IssueUrlParseException
     */
    public function getIssueNumber(string $url): int
    {
        if (!preg_match('#/issues/(?<number>\d+)$#', $url, $matches)) {
            throw new IssueUrlParseException($url);
        }

        return (int) $matches['number'];
    }

    /**
     * @param string $url
     *
     * @return string
     *
     * @throws IssueUrlParseException
     */
    public function getIssuePath(string $url): string
    {
        if (!preg_match('#/(?<user>[\w\-]+)/(?<repo>[\w\-]+)/issues/(?<number>\d+)$#', $url, $matches)) {
            throw new IssueUrlParseException($url);
        }

        return sprintf('%s/%s/%d', $matches['user'], $matches['repo'], $matches['number']);
    }
}
