<?php

namespace Pantono\Forms\Validator;

use Pantono\Forms\Config\ValidatorConfigSet;
use Pantono\Forms\Exception\Validation\AbstractValidationError;

abstract class AbstractValidator
{
    abstract public function getConfigSet(): ValidatorConfigSet;

    /**
     * @param mixed $input
     * @param array<string,mixed> $config
     * @return void
     * @throws AbstractValidationError If any validation errors are found
     */
    abstract public function doValidate(mixed $input, array $config = []): void;

    public function validator(mixed $input, array $config = []): void
    {
        $this->getConfigSet()->validateConfigArray($config);
        $this->doValidate($input, $config);
    }
}
