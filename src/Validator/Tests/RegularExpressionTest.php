<?php

namespace Pantono\Forms\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Pantono\Forms\Validator\RegularExpression;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class RegularExpressionTest extends TestCase
{
    public function testRegexMatch(): void
    {
        $validator = new RegularExpression();
        $validator->doValidate('abc', ['regex' => '/^[a-z]+$/']);
        $this->assertTrue(true);
    }

    public function testRegexNoMatch(): void
    {
        $validator = new RegularExpression();
        $this->expectException(GenericValidatorError::class);
        $this->expectExceptionMessage('Value does not match regular expression');
        $validator->doValidate('123', ['regex' => '/^[a-z]+$/']);
    }
}
