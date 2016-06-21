<?php

declare(strict_types=1);

namespace Wdnl\DoctrineEncryptBundle\Encryptor;

/**
 * Aes256 encryptor based on OpenSSL.
 */
class Aes256Encryptor implements EncryptorInterface
{
    /**
     * The MCRYPT mode.
     */
    const METHOD = 'AES-256-CFB';

    /**
     * @var string The encryption string
     */
    private $encryptionKey;

    /**
     * @var string The salt
     */
    private $salt;

    /**
     * @param string $encryptionKey
     * @param string $salt
     */
    public function __construct(
        string $encryptionKey,
        string $salt
    ) {
        $this->encryptionKey = $encryptionKey;
        $this->salt = $salt;
    }

    /**
     * @param string $payload
     *
     * @return string
     */
    public function encrypt(string $payload): string
    {
        $ivLength = openssl_cipher_iv_length(self::METHOD);
        $iv = substr(md5($this->encryptionKey), 0, $ivLength);

        return openssl_encrypt(
            $payload,
            self::METHOD,
            $this->salt,
            OPENSSL_ZERO_PADDING,
            $iv
        );
    }

    /**
     * @param string $payload
     *
     * @return string
     */
    public function decrypt(string $payload): string
    {
        $ivLength = openssl_cipher_iv_length(self::METHOD);
        $iv = substr(md5($this->encryptionKey), 0, $ivLength);

        return openssl_decrypt(
            $payload,
            self::METHOD,
            $this->salt,
            OPENSSL_ZERO_PADDING,
            $iv
        );
    }
}
