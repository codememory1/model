<?php

namespace Codememory\Components\Model\Interfaces;

use ReflectionClass;

/**
 * Interface ModelInterface
 *
 * @package Codememory\Components\Model\Interfaces
 *
 * @author  Codememory
 */
interface ModelInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the Reflection Class of the model, or if the class does
     * not exist, a ModelNotExistException will be thrown
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $name
     *
     * @return ReflectionClass
     */
    public function getModelReflector(string $name): ReflectionClass;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Generates and returns the complete model namespace
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $name
     *
     * @return string
     */
    public function generateModelNamespace(string $name): string;

}