<?php

namespace Wdnl\DoctrineEncryptBundle\Exception;

/**
 * Thrown when no encryptor has been provided.
 */
class MissingEncryptorException extends \Exception
{
    protected $message = 'Wdnl/DoctrineEncryptBundle requires an encryptor to function.';
}
