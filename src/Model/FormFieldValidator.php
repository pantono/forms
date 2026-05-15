<?php

namespace Pantono\Forms\Model;

use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Contracts\Attributes\Filter;

#[DatabaseTable('form_field_validator')]
class FormFieldValidator
{
    private ?int $id = null;
    private int $fieldId;
    #[OneToOne(targetModel: FormValidator::class), FieldName('validator_id')]
    private ?FormValidator $validator = null;
    /**
     * @var array<string, mixed>
     */
    #[Filter('json_decode')]
    private array $config = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    public function setFieldId(int $fieldId): void
    {
        $this->fieldId = $fieldId;
    }

    public function getValidator(): ?FormValidator
    {
        return $this->validator;
    }

    public function setValidator(?FormValidator $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function validate(mixed $input): void
    {
        $this->getValidator()->getValidator()->doValidate($input, $this->getConfig());
    }
}
