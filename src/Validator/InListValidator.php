<?php

namespace Pantono\Forms\Validator;

use Pantono\Forms\Config\ValidatorConfigSet;
use Pantono\Forms\Config\ValidatorConfigField;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class InListValidator extends AbstractValidator
{
    public function getConfigSet(): ValidatorConfigSet
    {
        $config = new ValidatorConfigSet();
        $config->addConfigField(new ValidatorConfigField('list', 'array', 'List of allowed values', true));
        return $config;
    }

    public function doValidate(mixed $input, array $config = []): void
    {
        $list = $config['list'];
        if (!in_array($input, $list)) {
            throw new GenericValidatorError('Value is not in the allowed list');
        }
    }
}
