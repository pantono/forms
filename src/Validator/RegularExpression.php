<?php

namespace Pantono\Forms\Validator;

use Pantono\Forms\Config\ValidatorConfigSet;
use Pantono\Forms\Config\ValidatorConfigField;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class RegularExpression extends AbstractValidator
{
    public function getConfigSet(): ValidatorConfigSet
    {
        $config = new ValidatorConfigSet();
        $config->addConfigField(new ValidatorConfigField('regex', 'string', 'Regular expression', true));
        return $config;
    }

    public function doValidate(mixed $input, array $config = []): void
    {
        $regex = $config['regex'];
        if (!preg_match($regex, $input)) {
            throw new GenericValidatorError('Value does not match regular expression');
        }
    }
}
