<?php

declare(strict_types=1);

namespace Wdnl\DoctrineEncryptBundle\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Wdnl\DoctrineEncryptBundle\Encryptor\EncryptorInterface;
use Wdnl\DoctrineEncryptBundle\Exception\MissingEncryptorException;

/**
 * The custom Doctrine type which is used to store values that must be
 * encrypted.
 *
 * Accepts encryptors which implement the EncryptorInterface (method encrypt
 * and decrypt).
 */
class Encrypted extends Type
{
    /**
     * Name of the custom Doctrine type.
     */
    const NAME = 'encrypted';

    /**
     * The Encryptor that is used. By default we use our own Aes256Encryptor.
     *
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     *
     * @throws MissingEncryptorException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return $this->getEncryptor()->decrypt($value);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     *
     * @throws MissingEncryptorException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return $this->getEncryptor()->encrypt($value);
    }

    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function getSQLDeclaration(
        array $fieldDeclaration,
        AbstractPlatform $platform
    ) {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @return EncryptorInterface
     *
     * @throws MissingEncryptorException
     */
    public function getEncryptor()
    {
        if (null === $this->encryptor || !($this->encryptor instanceof EncryptorInterface)) {
            throw new MissingEncryptorException();
        }

        return $this->encryptor;
    }

    /**
     * @param EncryptorInterface $encryptor
     *
     * @return Encrypted
     */
    public function setEncryptor(EncryptorInterface $encryptor)
    {
        $this->encryptor = $encryptor;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
