<?php

/*
 * (c) Vaidas Lažauskas <vaidas@notrix.lt>
 */

namespace App\Exception;

/**
 * App\Exception\IssueUrlParseException
 */
class IssueUrlParseException extends \Exception
{
    /**
     * Class constructor
     *
     * @param string          $issueUrl
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($issueUrl, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Could not parse issue URL: %s', $issueUrl),
            $code,
            $previous
        );
    }
}
