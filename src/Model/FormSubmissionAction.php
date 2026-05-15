<?php

namespace Pantono\Forms\Model;

use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Application\Interfaces\SavableInterface;

#[DatabaseTable('form_submission_action')]
class FormSubmissionAction implements SavableInterface
{
    use SavableModel;

    private ?int $id = null;
    private \DateTimeInterface $date;
    private int $submissionId;
    #[OneToOne(targetModel: FormAction::class), FieldName('action_id')]
    private ?FormAction $action = null;
    private bool $success;
    private ?string $output = null;
    private ?string $result = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getSubmissionId(): int
    {
        return $this->submissionId;
    }

    public function setSubmissionId(int $submissionId): void
    {
        $this->submissionId = $submissionId;
    }

    public function getAction(): ?FormAction
    {
        return $this->action;
    }

    public function setAction(?FormAction $action): void
    {
        $this->action = $action;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function setOutput(?string $output): void
    {
        $this->output = $output;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): void
    {
        $this->result = $result;
    }
}
