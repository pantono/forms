<?php

namespace Pantono\Forms\Model;

use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\OneToMany;
use Pantono\Contracts\Application\Interfaces\SavableInterface;
use Pantono\Database\Traits\SavableModel;

#[DatabaseTable('form_submission')]
class FormSubmission implements SavableInterface
{
    use SavableModel;

    private ?int $id = null;
    private int $formId;
    private \DateTimeInterface $date;
    /**
     * @var FormSubmissionField[]
     */
    #[OneToMany(targetModel: FormSubmissionField::class, mappedBy: 'submission_id')]
    private array $fields = [];

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

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    /**
     * @return FormSubmissionField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function addField(FormSubmissionField $field): void
    {
        $this->fields[] = $field;
    }

    /**
     * @return array<string,mixed>
     */
    public function getFieldArray(): array
    {
        $fields = [];
        foreach ($this->getFields() as $field) {
            $fields[$field->getField()->getName()] = $field->getValue();
        }
        return $fields;
    }
}
