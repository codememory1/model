<?php

namespace Codememory\Components\Model\Exceptions;

use ErrorException;
use JetBrains\PhpStorm\Pure;

/**
 * Class ModelException
 *
 * @package Codememory\Components\Model\Exceptions
 *
 * @author  Codememory
 */
abstract class ModelException extends ErrorException
{

    /**
     * @param string|null $message
     */
    #[Pure]
    public function __construct(string $message = null)
    {

        parent::__construct($message ?: '');

    }

}