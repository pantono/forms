<?php

namespace Pantono\Forms\Config;

class ValidatorConfigSet implements \JsonSerializable
{
    /**
     * @var ValidatorConfigField[]
     */
    private array $fields = [];

    public function addConfigField(ValidatorConfigField $field): self
    {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * @return ValidatorConfigField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function validateConfigArray(array $config): void
    {
        foreach ($this->getFields() as $field) {
            $value = $config[$field->getFieldName()] ?? null;
            if ($value === null && $field->isRequired()) {
                throw new \InvalidArgumentException(sprintf('Missing required configuration field "%s"', $field->getFieldName()));
            }
        }
    }

    /**
     * @return array<int, mixed>
     */
    public function jsonSerialize(): array
    {
        $fields = [];
        foreach ($this->getFields() as $field) {
            $fields[] = $field->jsonSerialize();
        }
        return $fields;
    }
}
