<?php

namespace Codememory\Components\Model\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class ModelNotExistException
 *
 * @package Codememory\Components\Model\Exceptions
 *
 * @author  Codememory
 */
class ModelNotExistException extends ModelException
{

    /**
     * @param string $model
     */
    #[Pure]
    public function __construct(string $model)
    {

        parent::__construct(sprintf('The %s model does not exist', $model));

    }

}