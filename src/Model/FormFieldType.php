<?php

namespace Pantono\Forms\Model;

use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\ManyToMany;

#[DatabaseTable('form_field_type')]
class FormFieldType
{
    private ?int $id = null;
    private string $name;
    private string $label;
    /**
     * @var FormValidator[]
     */
    #[ManyToMany(joinTable: 'form_field_type_validator', joinColumn: 'field_type_id', inverseJoinColumn: 'validator_id', targetModel: FormValidator::class)]
    private array $validators = [];

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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return FormValidator[]
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    public function setValidators(array $validators): void
    {
        $this->validators = $validators;
    }
}
