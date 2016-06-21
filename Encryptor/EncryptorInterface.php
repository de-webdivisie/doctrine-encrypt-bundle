<?php

declare(strict_types=1);

namespace Wdnl\DoctrineEncryptBundle\Encryptor;

/**
 * Interface for Encryptors.
 */
interface EncryptorInterface
{
    /**
     * @param string $encryptionKey
     * @param string $salt
     */
    public function __construct(
        string $encryptionKey,
        string $salt
    );

    /**
     * @param string $payload
     *
     * @return string Encrypted payload
     */
    public function encrypt(string $payload);

    /**
     * @param string $payload
     *
     * @return string Decrypted payload
     */
    public function decrypt(string $payload);
}
