<?php

namespace Codememory\Components\Model;

use Codememory\Components\Event\Dispatcher;
use Codememory\Components\Event\EventDispatcher;
use Codememory\Components\Event\Exceptions\EventExistException;
use Codememory\Components\Event\Exceptions\EventNotExistException;
use Codememory\Components\Event\Exceptions\EventNotImplementInterfaceException;
use Codememory\Components\Event\Interfaces\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @var Dispatcher
     */
    private Dispatcher $dispatcher;

    /**
     * @param ServiceProviderInterface $serviceProvider
     */
    public function __construct(ServiceProviderInterface $serviceProvider)
    {

        $this->serviceProvider = $serviceProvider;

        $this->model = new Model();
        $this->eventDispatcher = new EventDispatcher();
        $this->dispatcher = new Dispatcher();

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
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Execute event and listeners of the current event
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $eventNamespace
     * @param array  $parameters
     *
     * @throws ReflectionException
     * @throws EventExistException
     * @throws EventNotExistException
     * @throws EventNotImplementInterfaceException
     */
    protected function dispatchEvent(string $eventNamespace, array $parameters = []): void
    {

        $this->eventDispatcher->addEvent($eventNamespace)->setParameters($parameters);

        $event = $this->eventDispatcher->getEvent($eventNamespace);

        $this->dispatcher->dispatch($event);

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get a model inside another model
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $name
     *
     * @return AbstractModel
     * @throws Exceptions\ModelNotExistException
     * @throws ReflectionException
     */
    protected function getModel(string $name): AbstractModel
    {

        $modelReflector = $this->model->getModelReflector($name);

        /** @var AbstractModel $model */
        $model = $modelReflector->newInstanceArgs([
            $this->serviceProvider
        ]);

        return $model;

    }

}