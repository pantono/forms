<?php

namespace Pantono\Forms\Model;

use Pantono\Contracts\Application\Interfaces\SavableInterface;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Contracts\Attributes\Filter;

#[DatabaseTable('form_action')]
class FormAction implements SavableInterface
{
    use SavableModel;

    private ?int $id = null;
    private ?int $formId = null;
    #[OneToOne(targetModel: FormActionType::class), FieldName('type_id')]
    private ?FormActionType $actionType = null;
    private bool $enabled;
    /**
     * @var array<int,mixed>
     */
    #[Filter('json_decode')]
    private array $config;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFormId(): ?int
    {
        return $this->formId;
    }

    public function setFormId(?int $formId): void
    {
        $this->formId = $formId;
    }

    public function getActionType(): ?FormActionType
    {
        return $this->actionType;
    }

    public function setActionType(?FormActionType $actionType): void
    {
        $this->actionType = $actionType;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
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
}
