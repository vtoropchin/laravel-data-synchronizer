<?php declare(strict_types=1);


namespace App\Exceptions;


use Throwable;

/**
 * Class Exception
 * @package App\Exceptions
 */
class SystemException extends \Exception
{
    /**
     * SystemException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
