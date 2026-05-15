<?php

namespace Pantono\Forms\Validator;

use Pantono\Forms\Config\ValidatorConfigSet;
use Pantono\Forms\Config\ValidatorConfigField;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class MaximumNumberValidator extends AbstractValidator
{
    public function getConfigSet(): ValidatorConfigSet
    {
        $config = new ValidatorConfigSet();
        $config->addConfigField(new ValidatorConfigField('number', 'integer', 'Maximum number', true));
        return $config;
    }

    public function doValidate(mixed $input, array $config = []): void
    {
        $input = (int)$input;
        if ($input > $config['number']) {
            throw new GenericValidatorError('Value cannot be more than ' . $config['number']);
        }
    }
}
