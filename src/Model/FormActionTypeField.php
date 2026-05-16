<?php

namespace Pantono\Forms\Model;

use Pantono\Contracts\Application\Interfaces\SavableInterface;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\DatabaseTable;

#[DatabaseTable('form_action_type_field')]
class FormActionTypeField implements SavableInterface
{
    use SavableModel;

    private ?int $id = null;
    private int $formActionTypeId;
    private string $name;
    private string $type;
    private string $label;
    private bool $required;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFormActionTypeId(): int
    {
        return $this->formActionTypeId;
    }

    public function setFormActionTypeId(int $formActionTypeId): void
    {
        $this->formActionTypeId = $formActionTypeId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
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
}
