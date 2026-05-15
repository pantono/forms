<?php

namespace Pantono\Forms\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Pantono\Forms\Validator\InListValidator;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class InListValidatorTest extends TestCase
{
    private InListValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new InListValidator();
    }

    public function testValueInList(): void
    {
        $this->validator->doValidate('a', ['list' => ['a', 'b', 'c']]);
        $this->assertTrue(true);
    }

    public function testValueNotInList(): void
    {
        $this->expectException(GenericValidatorError::class);
        $this->expectExceptionMessage('Value is not in the allowed list');
        $this->validator->doValidate('d', ['list' => ['a', 'b', 'c']]);
    }
}
