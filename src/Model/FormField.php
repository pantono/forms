<?php

namespace Pantono\Forms\Model;

use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\Filter;
use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Application\Interfaces\SavableInterface;
use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Contracts\Attributes\Database\OneToMany;

#[DatabaseTable('form_field')]
class FormField implements SavableInterface
{
    use SavableModel;

    private ?int $id = null;
    private int $formId;
    #[OneToOne(targetModel: FormFieldType::class), FieldName('field_type_id')]
    private ?FormFieldType $fieldType = null;
    private string $name;
    private string $label;
    private bool $required;
    /**
     * @var array<mixed>
     */
    #[Filter('json_decode')]
    private array $config = [];
    /**
     * @var FormValidator[]
     */
    #[OneToMany(targetModel: FormValidator::class, mappedBy: 'field_id')]
    private array $validators = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFormId(): int
    {
        return $this->formId;
    }

    public function setFormId(int $formId): void
    {
        $this->formId = $formId;
    }

    public function getFieldType(): ?FormFieldType
    {
        return $this->fieldType;
    }

    public function setFieldType(?FormFieldType $fieldType): void
    {
        $this->fieldType = $fieldType;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    /**
     * @return mixed[]
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
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
