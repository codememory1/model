<?php

namespace Codememory\Components\Model;

use Codememory\Components\Model\Exceptions\ModelNotExistException;
use Codememory\Components\Model\Interfaces\ModelInterface;
use ReflectionClass;

/**
 * Class Model
 *
 * @package Codememory\Components\Model
 *
 * @author  Codememory
 */
class Model implements ModelInterface
{

    /**
     * @var Utils
     */
    private Utils $utils;

    /**
     * Model Construct
     */
    public function __construct()
    {

        $this->utils = new Utils();

    }

    /**
     * @inheritDoc
     * @throws ModelNotExistException
     */
    public function getModelReflector(string $name): ReflectionClass
    {

        $modelNamespace = $this->generateModelNamespace($name);

        if (!class_exists($modelNamespace)) {
            throw new ModelNotExistException($modelNamespace);
        }

        return new ReflectionClass($modelNamespace);

    }

    /**
     * @inheritDoc
     */
    public function generateModelNamespace(string $name): string
    {

        $modelNamespace = $this->utils->getNamespaceModel();
        $modelSuffix = $this->utils->getModelSuffix();

        return $modelNamespace . $name . $modelSuffix;

    }

}