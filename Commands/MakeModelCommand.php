<?php

namespace Codememory\Components\Model\Commands;

use Codememory\Components\Console\Command;
use Codememory\Components\Model\Utils;
use Codememory\FileSystem\File;
use Codememory\FileSystem\Interfaces\FileInterface;
use Codememory\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MakeModelCommand
 *
 * @package Codememory\Components\Model\Commands
 *
 * @author  Codememory
 */
class MakeModelCommand extends Command
{

    /**
     * @inheritDoc
     */
    protected ?string $command = 'make:model';

    /**
     * @inheritDoc
     */
    protected ?string $description = 'Create model';

    /**
     * @inheritDoc
     */
    protected function wrapArgsAndOptions(): Command
    {

        $this->addArgument('name', InputArgument::REQUIRED, 'Model name without suffix');
        $this->addOption('re-create', null, InputOption::VALUE_NONE, 'Recreate the model if a model with the same name already exists');

        return $this;

    }

    /**
     * @inheritDoc
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $filesystem = new File();
        $utils = new Utils();
        $modelName = $input->getArgument('name');
        $className = $modelName . $utils->getModelSuffix();
        $fullPath = sprintf('%s%s.php', $utils->getPathWithModels(), $className);
        $namespace = Str::trimAfterSymbol($utils->getNamespaceModel(), '\\', false);

        if (!$filesystem->exist($utils->getPathWithModels())) {
            $filesystem->mkdir($utils->getPathWithModels(), 0777, true);
        }

        $stubModel = $this->getBuiltModel($namespace, $className, $filesystem);

        if ($filesystem->exist($fullPath) && !$input->getOption('re-create')) {
            $this->io->error(sprintf('A model named %s already exists', $modelName));

            return Command::FAILURE;
        }

        file_put_contents($fullPath, $stubModel);

        $this->io->success([
            sprintf('Model %s created successfully', $modelName),
            sprintf('Path: %s', $fullPath)
        ]);

        return Command::SUCCESS;

    }

    /**
     * @param string        $namespace
     * @param string        $className
     * @param FileInterface $filesystem
     *
     * @return string
     */
    private function getBuiltModel(string $namespace, string $className, FileInterface $filesystem): string
    {

        return str_replace([
            '{namespace}',
            '{className}'
        ], [
            $namespace,
            $className
        ], $this->getModelStub($filesystem));

    }

    /**
     * @param FileInterface $filesystem
     *
     * @return string
     */
    private function getModelStub(FileInterface $filesystem): string
    {

        return file_get_contents($filesystem->getRealPath('/vendor/codememory/model/Commands/Stubs/ModelStub.stub'));

    }

}