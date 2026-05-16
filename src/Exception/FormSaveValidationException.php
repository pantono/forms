<?php

namespace Pantono\Forms\Exception;

class FormSaveValidationException extends \RuntimeException
{
    /**
     * @var string[]
     */
    private array $errors;

    public function __construct(array $fields, string $message)
    {
        $this->errors = $fields;
        parent::__construct($message);
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
