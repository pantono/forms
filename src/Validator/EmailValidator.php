<?php

namespace Pantono\Forms\Validator;

use Pantono\Forms\Config\ValidatorConfigSet;
use Pantono\Forms\Config\ValidatorConfigField;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class EmailValidator extends AbstractValidator
{
    public function getConfigSet(): ValidatorConfigSet
    {
        $config = new ValidatorConfigSet();
        $config->addConfigField(new ValidatorConfigField('allow_temp_email_domain', 'boolean', 'Allow Temporary Email Domains', false));
        return $config;
    }

    public function doValidate(mixed $input, array $config = []): void
    {
        if (!$input) {
            throw new GenericValidatorError('Email address cannot be empty');
        }
        if (filter_var($input, FILTER_VALIDATE_EMAIL) !== $input) {
            throw new GenericValidatorError('Invalid e-mail address');
        }
    }
}
