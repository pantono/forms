<?php

namespace Pantono\Forms\Model;

use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\OneToMany;

#[DatabaseTable('form_action_type')]
class FormActionType
{
    private ?int $id = null;

    private string $name;
    private string $controller;
    /**
     * @var FormActionTypeField[]
     */
    #[OneToMany(targetModel: FormActionTypeField::class, mappedBy: 'type_id')]
    private array $fields = [];

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

    /**
     * @return FormActionTypeField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }
}
