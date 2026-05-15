<?php

namespace Pantono\Forms\Model;

use Pantono\Forms\Exception\ValidatorControllerDoesNotExist;
use Pantono\Forms\Config\ValidatorConfigSet;
use Pantono\Forms\Validator\AbstractValidator;
use Pantono\Contracts\Attributes\Locator;
use Pantono\Forms\Forms;
use Pantono\Contracts\Attributes\NoSave;
use Pantono\Contracts\Attributes\Lazy;
use Pantono\Contracts\Attributes\FieldName;

class FormValidator
{
    private ?int $id = null;
    private string $name;
    private string $controller;
    #[Locator(methodName: 'createValidatorClass', className: Forms::class), NoSave, Lazy, FieldName('$this')]
    private ?AbstractValidator $validator = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function getRequiredConfig(): ValidatorConfigSet
    {
        if (!class_exists($this->getController())) {
            throw new ValidatorControllerDoesNotExist('Validator controller does not exist');
        }
        return $this->getValidator()->getConfigSet();
    }

    public function getValidator(): ?AbstractValidator
    {
        return $this->validator;
    }

    public function setValidator(?AbstractValidator $validator): void
    {
        $this->validator = $validator;
    }
}
