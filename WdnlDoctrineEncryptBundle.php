<?php

namespace Wdnl\DoctrineEncryptBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wdnl\DoctrineEncryptBundle\Type\Encrypted;

/**
 * Bundle which is used to encrypt and decrypt properties of Doctrine fields.
 *
 * @todo move to Packagist
 */
class WdnlDoctrineEncryptBundle extends Bundle
{
    /**
     * Registers the custom Encrypted type.
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function boot()
    {
        // Prevents Exception for defining the Encrypted type multiple times.
        // This method gets called multiple times for cache purposes.
        if (false === Type::hasType(Encrypted::NAME)) {
            Type::addType(
                Encrypted::NAME,
                'Wdnl\DoctrineEncryptBundle\Type\Encrypted'
            );
        }

        /** @var Encrypted $encryptedType */
        $encryptedType = Type::getType(Encrypted::NAME);
        $aes256Encryptor = $this->container->get(
            'wdnl.doctrine_encrypt.aes256_encryptor'
        );
        $encryptedType->setEncryptor($aes256Encryptor);

        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $platform = $entityManager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping(
            Encrypted::NAME,
            Encrypted::NAME
        );
        $platform->markDoctrineTypeCommented(Encrypted::NAME);
    }
}
