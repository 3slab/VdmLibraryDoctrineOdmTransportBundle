<?php

/**
 * @package    3slab/VdmLibraryDoctrineOdmTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryDoctrineOdmTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Transport;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Executor\AbstractDoctrineExecutor;
use Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Transport\DoctrineSender;

class DoctrineSenderFactory
{
    /**
     * @var AbstractDoctrineExecutor
     */
    protected $entityManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        AbstractDoctrineExecutor $executor,
        LoggerInterface $logger = null
    ) {
        $this->executor = $executor;
        $this->logger   = $logger ?? new NullLogger();
    }

    /**
     * Created the DoctrineSender object based on messenger configuration.
     *
     * @param  array  $options
     *
     * @return DoctrineSender
     */
    public function createDoctrineSender(): DoctrineSender
    {
        $sender = new DoctrineSender($this->executor, $this->logger);

        return $sender;
    }
}
