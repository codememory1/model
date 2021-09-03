<?php

namespace Codememory\Components\Model;

use Codememory\Components\Configuration\Configuration;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\GlobalConfig\GlobalConfig;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Utils
 *
 * @package Codememory\Components\Model
 *
 * @author  Codememory
 */
class Utils
{

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * Utils Construct.
     */
    public function __construct()
    {

        $this->config = Configuration::getInstance()->open(GlobalConfig::get('model.configName'), $this->defaultConfig());

    }

    /**
     * @return string
     */
    public function getPathWithModels(): string
    {

        return trim($this->config->get('pathWithModels'), '/') . '/';

    }

    /**
     * @return string
     */
    public function getNamespaceModel(): string
    {

        return trim($this->config->get('namespaceModel'), '\\') . '\\';

    }

    /**
     * @return string
     */
    public function getModelSuffix(): string
    {

        return $this->config->get('modelSuffix');

    }

    /**
     * @return array
     */
    #[ArrayShape(['pathWithModels' => "mixed", 'namespaceModel' => "mixed", 'modelSuffix' => "mixed"])]
    private function defaultConfig(): array
    {

        return [
            'pathWithModels' => GlobalConfig::get('model.pathWithModels'),
            'namespaceModel' => GlobalConfig::get('model.namespaceModel'),
            'modelSuffix'    => GlobalConfig::get('model.modelSuffix')
        ];

    }

}