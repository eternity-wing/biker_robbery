<?php

namespace App\Exception;

use Throwable;

/**
 * Class TransactionException.
 *
 * @author Wings <Eternity.mr8@gmail.com>
 */
class TransactionException extends \Exception
{
    public function __construct($message = 'An exception occures during running transaction', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
