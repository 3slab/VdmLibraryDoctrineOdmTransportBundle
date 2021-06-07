<?php

/**
 * @package    3slab/VdmLibraryDoctrineOdmTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryDoctrineOdmTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryDoctrineOdmTransportBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Executor\AbstractDoctrineExecutor;

/**
 * Class VdmLibraryExtension
 *
 * @package Vdm\Bundle\LibraryDoctrineOdmTransportBundle\DependencyInjection
 */
class VdmLibraryDoctrineOdmTransportExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(AbstractDoctrineExecutor::class)
            ->addTag('vdm_library.doctrine_odm_executor')
        ;

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'vdm_library_doctrine_odm_transport';
    }
}
