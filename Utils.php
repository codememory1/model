<?php

namespace Codememory\Components\Model;

use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Configuration\Config;
use Codememory\Components\Configuration\Exceptions\ConfigNotFoundException;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\IncorrectPathToEnviException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\Components\GlobalConfig\GlobalConfig;
use Codememory\FileSystem\File;
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
     * @throws ConfigPathNotExistException
     * @throws ConfigNotFoundException
     * @throws EnvironmentVariableNotFoundException
     * @throws IncorrectPathToEnviException
     * @throws ParsingErrorException
     * @throws VariableParsingErrorException
     */
    public function __construct()
    {

        $config = new Config(new File());

        $this->config = $config->open(GlobalConfig::get('model.configName'), $this->defaultConfig());

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