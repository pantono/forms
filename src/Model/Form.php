<?php

namespace Pantono\Forms\Model;

use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Application\Interfaces\SavableInterface;
use Pantono\Contracts\Attributes\Database\OneToMany;
use Pantono\Database\Traits\SavableModel;

#[DatabaseTable('form')]
class Form implements SavableInterface
{
    use SavableModel;

    private ?int $id = null;
    private string $name;
    private string $introCopy;
    private ?string $successMessage = null;
    private ?string $failureMessage = null;
    /**
     * @var FormField[]
     */
    #[OneToMany(targetModel: FormField::class, mappedBy: 'form_id')]
    private array $fields = [];
    /**
     * @var FormAction[]
     */
    #[OneToMany(targetModel: FormAction::class, mappedBy: 'form_id')]
    private array $actions = [];

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

    public function getIntroCopy(): string
    {
        return $this->introCopy;
    }

    public function setIntroCopy(string $introCopy): void
    {
        $this->introCopy = $introCopy;
    }

    public function getSuccessMessage(): ?string
    {
        return $this->successMessage;
    }

    public function setSuccessMessage(?string $successMessage): void
    {
        $this->successMessage = $successMessage;
    }

    public function getFailureMessage(): ?string
    {
        return $this->failureMessage;
    }

    public function setFailureMessage(?string $failureMessage): void
    {
        $this->failureMessage = $failureMessage;
    }

    /**
     * @return FormField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @return FormAction[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function setActions(array $actions): void
    {
        $this->actions = $actions;
    }
}
