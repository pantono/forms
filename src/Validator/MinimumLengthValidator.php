<?php

namespace Pantono\Forms\Validator;

use Pantono\Forms\Config\ValidatorConfigSet;
use Pantono\Forms\Config\ValidatorConfigField;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class MinimumLengthValidator extends AbstractValidator
{
    public function getConfigSet(): ValidatorConfigSet
    {
        $config = new ValidatorConfigSet();
        $config->addConfigField(new ValidatorConfigField('length', 'integer', 'Minimum length', true));
        return $config;
    }

    public function doValidate(mixed $input, array $config = []): void
    {
        if (mb_strlen($input) < $config['length']) {
            throw new GenericValidatorError('Value must at least ' . $config['length'] . ' characters long');
        }
    }
}
