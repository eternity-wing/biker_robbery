<?php

namespace App\Exception;

use Throwable;

/**
 * Class InvalidRequestBodyException.
 *
 * @author Wings <Eternity.mr8@gmail.com>
 */
class InvalidRequestBodyException extends \Exception
{
    /**
     * InvalidRequestBodyException constructor.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct($message = 'Invalid request body exception', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
