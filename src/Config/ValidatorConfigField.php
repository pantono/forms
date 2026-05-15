<?php

namespace Pantono\Forms\Config;

class ValidatorConfigField implements \JsonSerializable
{
    private string $fieldName;
    private string $fieldType;
    /**
     * @var array<string,mixed>
     */
    private array $config;
    private string $label;
    private bool $required;

    public function __construct(string $fieldName, string $fieldType, string $label, bool $required, array $config = [])
    {
        $this->fieldName = $fieldName;
        $this->fieldType = $fieldType;
        $this->config = $config;
        $this->required = $required;
        $this->label = $label;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    public function setFieldType(string $fieldType): void
    {
        $this->fieldType = $fieldType;
    }

    /**
     * @return array<string,mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
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
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'field_name' => $this->getFieldName(),
            'field_type' => $this->getFieldType(),
            'config' => $this->getConfig()
        ];
    }

}
