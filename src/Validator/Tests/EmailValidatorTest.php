<?php

namespace Pantono\Forms\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Pantono\Forms\Validator\EmailValidator;
use Pantono\Forms\Exception\Validation\GenericValidatorError;

class EmailValidatorTest extends TestCase
{
    private EmailValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new EmailValidator();
    }

    public function testValidEmail(): void
    {
        $this->validator->doValidate('test@example.com');
        $this->assertTrue(true); // If no exception is thrown, it passes
    }

    public function testEmptyEmail(): void
    {
        $this->expectException(GenericValidatorError::class);
        $this->expectExceptionMessage('Email address cannot be empty');
        $this->validator->doValidate('');
    }

    public function testInvalidEmail(): void
    {
        $this->expectException(GenericValidatorError::class);
        $this->expectExceptionMessage('Invalid e-mail address');
        $this->validator->doValidate('invalid-email');
    }

    public function testGetConfigSet(): void
    {
        $configSet = $this->validator->getConfigSet();
        $this->assertCount(1, $configSet->getFields());
        $this->assertEquals('allow_temp_email_domain', $configSet->getFields()[0]->getFieldName());
    }
}
