<?php

namespace Codememory\Components\Model;

use Codememory\Components\Model\Interfaces\ModelInterface;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use ReflectionException;

/**
 * Class AbstractModel
 *
 * @package Codememory\Components\Model
 *
 * @author  Codememory
 */
abstract class AbstractModel
{

    /**
     * @var ServiceProviderInterface
     */
    private ServiceProviderInterface $serviceProvider;

    /**
     * @var ModelInterface
     */
    private ModelInterface $model;

    /**
     * @param ServiceProviderInterface $serviceProvider
     */
    public function __construct(ServiceProviderInterface $serviceProvider)
    {

        $this->serviceProvider = $serviceProvider;
        $this->model = new Model();

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get a provider by name
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $name
     *
     * @return object
     */
    protected function get(string $name): object
    {

        return $this->serviceProvider->get($name);

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get a model inside another model
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $name
     *
     * @return ModelInterface
     * @throws Exceptions\ModelNotExistException
     * @throws ReflectionException
     */
    protected function getModel(string $name): ModelInterface
    {

        $modelReflector = $this->model->getModelReflector($name);

        /** @var ModelInterface $model */
        $model = $modelReflector->newInstanceArgs([
            $this->serviceProvider
        ]);

        return $model;

    }

}