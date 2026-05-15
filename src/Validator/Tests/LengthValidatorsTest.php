<?php

namespace Pantono\Forms\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Pantono\Forms\Validator\MaximumLengthValidator;
use Pantono\Forms\Validator\MinimumLengthValidator;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class LengthValidatorsTest extends TestCase
{
    public function testMaximumLengthValid(): void
    {
        $validator = new MaximumLengthValidator();
        $validator->doValidate('abc', ['length' => 3]);
        $this->assertTrue(true);
    }

    public function testMaximumLengthInvalid(): void
    {
        $validator = new MaximumLengthValidator();
        $this->expectException(GenericValidatorError::class);
        $this->expectExceptionMessage('Value is too long');
        $validator->doValidate('abcd', ['length' => 3]);
    }

    public function testMinimumLengthValid(): void
    {
        $validator = new MinimumLengthValidator();
        $validator->doValidate('abc', ['length' => 3]);
        $this->assertTrue(true);
    }

    public function testMinimumLengthInvalid(): void
    {
        $validator = new MinimumLengthValidator();
        $this->expectException(GenericValidatorError::class);
        $this->expectExceptionMessage('Value must at least 3 characters long');
        $validator->doValidate('ab', ['length' => 3]);
    }
}
