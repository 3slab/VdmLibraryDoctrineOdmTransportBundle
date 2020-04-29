<?php

/**
 * @package    3slab/VdmLibraryDoctrineOdmTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryDoctrineOdmTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryDoctrineOdmTransportBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vdm\Bundle\LibraryDoctrineOdmTransportBundle\DependencyInjection\Compiler\SetDoctrineExecutorCompilerPass;

class VdmLibraryDoctrineOdmTransportBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SetDoctrineExecutorCompilerPass());
    }
}
