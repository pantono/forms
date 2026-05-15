<?php

namespace Pantono\Forms\Model;

use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Contracts\Attributes\FieldName;

#[DatabaseTable('form_submission_field')]
class FormSubmissionField
{
    private ?int $id = null;
    private int $submissionId;
    #[OneToOne(targetModel: FormField::class), FieldName('field_id')]
    private FormField $field;
    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getSubmissionId(): int
    {
        return $this->submissionId;
    }

    public function setSubmissionId(int $submissionId): void
    {
        $this->submissionId = $submissionId;
    }

    public function getField(): FormField
    {
        return $this->field;
    }

    public function setField(FormField $field): void
    {
        $this->field = $field;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
}
