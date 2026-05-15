<?php

namespace Pantono\Forms\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Pantono\Forms\Validator\MaximumNumberValidator;
use Pantono\Forms\Validator\MinimumNumberValidator;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class NumberValidatorsTest extends TestCase
{
    public function testMaximumNumberValid(): void
    {
        $validator = new MaximumNumberValidator();
        $validator->doValidate(10, ['number' => 10]);
        $this->assertTrue(true);
    }

    public function testMaximumNumberInvalid(): void
    {
        $validator = new MaximumNumberValidator();
        $this->expectException(GenericValidatorError::class);
        $this->expectExceptionMessage('Value cannot be more than 10');
        $validator->doValidate(11, ['number' => 10]);
    }

    public function testMinimumNumberValid(): void
    {
        $validator = new MinimumNumberValidator();
        $validator->doValidate(10, ['number' => 10]);
        $this->assertTrue(true);
    }

    public function testMinimumNumberInvalid(): void
    {
        $validator = new MinimumNumberValidator();
        $this->expectException(GenericValidatorError::class);
        $this->expectExceptionMessage('Value is too small');
        $validator->doValidate(9, ['number' => 10]);
    }
}
