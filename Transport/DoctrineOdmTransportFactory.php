<?php

/**
 * @package    3slab/VdmLibraryDoctrineOdmTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryDoctrineOdmTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Transport;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializer;
use Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Exception\UndefinedEntityException;
use Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Executor\AbstractDoctrineExecutor;
use Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Executor\DoctrineExecutorConfigurator;
use Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Transport\DoctrineSenderFactory;
use Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Transport\DoctrineTransport;

class DoctrineOdmTransportFactory implements TransportFactoryInterface
{
    protected const DSN_PROTOCOL_DOCTRINE = 'vdm+doctrine_odm://';
    protected const DSN_PATTERN_MATCHING  = '/(?P<protocol>[^:]+:\/\/)(?P<connection>.*)/';

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var ManagerRegistry $doctrine
     */
    protected $doctrine;

    /**
     * @var AbstractDoctrineExecutor $executor
     */
    protected $executor;

    /**
     * @param LoggerInterface          $logger
     * @param ManagerRegistry          $doctrine
     * @param AbstractDoctrineExecutor $executor
     * @param SymfonySerializer        $serializer
     */
    public function __construct(
        LoggerInterface $logger,
        ManagerRegistry $doctrine,
        AbstractDoctrineExecutor $executor,
        SymfonySerializer $serializer
    ) {
        $this->logger     = $logger;
        $this->doctrine   = $doctrine;
        $this->executor   = $executor;
        $this->serializer = $serializer;
    }

    /**
     * Creates DoctrineTransport
     * @param  string              $dsn
     * @param  array               $options
     * @param  SerializerInterface $serializer
     *
     * @return TransportInterface
     */
    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        if (empty($options['entities'])) {
            $errorMessage = sprintf('%s requires that you define at least one entity value in the transport\'s options.', __CLASS__);
            throw new UndefinedEntityException($errorMessage);
        }

        unset($options['transport_name']);

        $manager      = $this->getManager($dsn);

        $configurator = new DoctrineExecutorConfigurator($manager, $this->logger, $this->serializer, $options);
        $configurator->configure($this->executor);

        $doctrineSenderFactory = new DoctrineSenderFactory($this->executor, $this->logger);
        $doctrineSender        = $doctrineSenderFactory->createDoctrineSender();

        return new DoctrineTransport($doctrineSender, $this->logger);
    }

    /**
     * Tests if DSN is valid (protocol and valid Doctrine connection).
     *
     * @param string $dsn
     * @param array  $options
     *
     * @return bool
     */
    public function supports(string $dsn, array $options): bool
    {
        preg_match(static::DSN_PATTERN_MATCHING, $dsn, $match);

        if (0 === strpos($match['protocol'], static::DSN_PROTOCOL_DOCTRINE)) {
            // No need to put it in a variable now. If the connection doesn't exist, Doctrine will throw an exception
            $this->getManager($dsn);

            // If we passe the if statement, and getManager(), we're good. 
            return true;
        }

        // Otherwise, tranport not supported.
        return false;
    }

    /**
     * Returns the manager from Doctrine registry.
     *
     * @param  string $dsn
     *
     * @throws InvalidArgumentException invalid connection
     *
     * @return ObjectManager
     */
    protected function getManager(string $dsn): ObjectManager
    {
        preg_match(static::DSN_PATTERN_MATCHING, $dsn, $match);
        
        $match['connection'] = $match['connection'] ?: 'default';

        $manager = $this->doctrine->getManager($match['connection']);

        return $manager;
    }
}
