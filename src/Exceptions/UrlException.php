<?php

namespace sergiobelya\TestHexaImageloader\Exceptions;

/**
 * @author Serg
 */
class UrlException extends AbstractException
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct('URL '.$message.' is not correct image url', $code, $previous);
    }
}
