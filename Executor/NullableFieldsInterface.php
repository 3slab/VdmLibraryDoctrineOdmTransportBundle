<?php

/**
 * @package    3slab/VdmLibraryDoctrineOdmTransportBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmLibraryDoctrineOdmTransportBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Executor;

/**
 * Interface NullableFieldsInterface
 * @package Vdm\Bundle\LibraryDoctrineOdmTransportBundle\Executor
 */
interface NullableFieldsInterface
{
    public static function getNullableFields(): array;
}
