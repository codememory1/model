<?php

namespace Codememory\Components\Model;

use Codememory\Components\Event\Dispatcher;
use Codememory\Components\Event\EventDispatcher;
use Codememory\Components\Event\Exceptions\EventExistException;
use Codememory\Components\Event\Exceptions\EventNotExistException;
use Codememory\Components\Event\Exceptions\EventNotImplementInterfaceException;
use Codememory\Components\Event\Interfaces\EventDataInterface;
use Codememory\Components\Event\Interfaces\EventDispatcherInterface;
use Codememory\Components\Model\Interfaces\ModelInterface;
use Codememory\Components\Profiling\Exceptions\BuilderNotCurrentSectionException;
use Codememory\Components\Profiling\ReportCreators\EventsReportCreator;
use Codememory\Components\Profiling\Resource;
use Codememory\Components\Profiling\Sections\Builders\EventsBuilder;
use Codememory\Components\Profiling\Sections\EventsSection;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use ReflectionException;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;

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
     * @throws BuilderNotCurrentSectionException
     * @throws EventExistException
     * @throws EventNotExistException
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    protected function dispatchEvent(string $eventNamespace, array $parameters = []): void
    {

        $microTime = microtime(true);
        $this->eventDispatcher->addEvent($eventNamespace)->setParameters($parameters);

        $event = $this->eventDispatcher->getEvent($eventNamespace);

        $this->dispatcher->dispatch($event);

        $this->eventProfiling($event, $microTime);

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

    /**
     * @param EventDataInterface $event
     * @param float              $microTime
     *
     * @return void
     * @throws BuilderNotCurrentSectionException
     */
    private function eventProfiling(EventDataInterface $event, float $microTime): void
    {

        $eventsReportCreator = new EventsReportCreator(null, new EventsSection(new Resource()));
        $eventsBuilder = new EventsBuilder();

        /** @var Frame $demanded */
        $demanded = Backtrace::create()
            ->startingFromFrame(function (Frame $frame) {
                return $frame->class === static::class;
            })
            ->frames()[0];

        $eventsBuilder
            ->setEvent($event->getNamespace())
            ->setListeners(array_map(function (object|string $listener) {
                return is_callable($listener) ? 'callback' : $listener::class;
            }, $event->getListeners()))
            ->setDemanded($demanded->class, $demanded->method)
            ->setLeadTime(round((microtime(true) - $microTime) * 1000));

        $eventsReportCreator->create($eventsBuilder);

    }

}