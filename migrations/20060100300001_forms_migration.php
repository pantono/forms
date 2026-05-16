<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;
use Pantono\Forms\Validator\MinimumLengthValidator;
use Pantono\Forms\Validator\MaximumLengthValidator;
use Pantono\Forms\Validator\RegularExpression;
use Pantono\Forms\Validator\MinimumNumberValidator;
use Pantono\Forms\Validator\MaximumNumberValidator;
use Pantono\Forms\Validator\InListValidator;
use Pantono\Forms\Validator\EmailValidator;
use Pantono\Forms\Actions\SendEmailActionController;
use Pantono\Forms\Actions\CreateQueueTaskActionController;
use Pantono\Forms\Actions\WebhookActionController;

final class FormsMigration extends BasePantonoMigration
{
    public function change(): void
    {
        $this->tablePrefix('form_validator')
            ->addColumn('name', 'string')
            ->addColumn('controller', 'string')
            ->create();

        $this->insertOnCreate($this->addTablePrefix('form_validator'), [
            ['id' => 1, 'name' => 'Minimum Length', 'controller' => MinimumLengthValidator::class],
            ['id' => 2, 'name' => 'Maximum Length', 'controller' => MaximumLengthValidator::class],
            ['id' => 3, 'name' => 'Regular Expression', 'controller' => RegularExpression::class],
            ['id' => 4, 'name' => 'Minimum Number', 'controller' => MinimumNumberValidator::class],
            ['id' => 5, 'name' => 'Maximum Number', 'controller' => MaximumNumberValidator::class],
            ['id' => 6, 'name' => 'In List', 'controller' => InListValidator::class],
            ['id' => 7, 'name' => 'Valid Email Address', 'controller' => EmailValidator::class]
        ]);

        $this->tablePrefix('form_field_type')
            ->addColumn('name', 'string')
            ->addColumn('label', 'string')
            ->create();

        $this->insertOnCreate($this->addTablePrefix('form_field_type'), [
            ['id' => 1, 'name' => 'text', 'label' => 'Text'],
            ['id' => 2, 'name' => 'number', 'label' => 'Number'],
            ['id' => 3, 'name' => 'select', 'label' => 'Select'],
            ['id' => 4, 'name' => 'checkbox', 'label' => 'Checkbox'],
            ['id' => 5, 'name' => 'textarea', 'label' => 'Textarea'],
        ]);

        $this->tablePrefix('form_field_type_validator', ['id' => false])
            ->addLinkedColumn('field_type_id', 'form_field_type', 'id')
            ->addLinkedColumn('validator_id', 'form_validator', 'id')
            ->create();

        if ($this->isMigratingUp()) {
            $this->table($this->addTablePrefix('form_field_type_validator'))->insert([
                ['field_type_id' => 1, 'validator_id' => 1],
                ['field_type_id' => 1, 'validator_id' => 2],
                ['field_type_id' => 1, 'validator_id' => 3],
                ['field_type_id' => 1, 'validator_id' => 7],
                ['field_type_id' => 2, 'validator_id' => 4],
                ['field_type_id' => 2, 'validator_id' => 5],
                ['field_type_id' => 5, 'validator_id' => 3],
            ])->saveData();
        }

        $this->tablePrefix('form')
            ->addColumn('name', 'string')
            ->addColumn('intro_copy', 'text')
            ->addColumn('success_message', 'string', ['null' => true])
            ->addColumn('save_button_text', 'string', ['null' => true])
            ->create();

        $this->tablePrefix('form_field')
            ->addLinkedColumn('form_id', 'form', 'id')
            ->addLinkedColumn('field_type_id', 'form_field_type', 'id')
            ->addColumn('name', 'string')
            ->addColumn('label', 'string')
            ->addColumn('required', 'boolean')
            ->addColumn('config', 'json')
            ->create();

        $this->tablePrefix('form_field_validator')
            ->addLinkedColumn('field_id', 'form_field', 'id')
            ->addLinkedColumn('validator_id', 'form_validator', 'id')
            ->addColumn('config', 'json')
            ->create();

        $this->tablePrefix('form_action_type')
            ->addColumn('name', 'string')
            ->addColumn('controller', 'string')
            ->create();

        $this->insertOnCreate($this->addTablePrefix('form_action_type'), [
            ['id' => 1, 'name' => 'Send Email', 'controller' => SendEmailActionController::class],
            ['id' => 2, 'name' => 'Create Queue Task', 'controller' => CreateQueueTaskActionController::class],
            ['id' => 3, 'name' => 'Send Webhook', 'controller' => WebhookActionController::class]
        ]);

        $this->tablePrefix('form_action_type_field')
            ->addLinkedColumn('type_id', $this->addTablePrefix('form_action_type'), 'id')
            ->addColumn('type', 'string')
            ->addColumn('name', 'string')
            ->addColumn('label', 'string')
            ->addColumn('required', 'boolean')
            ->create();

        $this->insertOnCreate($this->addTablePrefix('form_action_type_field'), [
            ['type_id' => 1, 'type' => 'email_address', 'name' => 'to_address', 'label' => 'To Address', 'required' => 1],
            ['type_id' => 1, 'type' => 'email_template', 'name' => 'email_template', 'label' => 'Email Template', 'required' => 1],
            ['type_id' => 1, 'type' => 'text', 'name' => 'subject', 'label' => 'Subject', 'required' => 1],
            ['type_id' => 2, 'type' => 'text', 'name' => 'task_name', 'label' => 'Task Name', 'required' => 1],
            ['type_id' => 2, 'type' => 'json', 'name' => 'additional_context', 'label' => 'Additional Context', 'required' => 0],
            ['type_id' => 3, 'type' => 'text', 'name' => 'url', 'label' => 'URL', 'required' => 1],
            ['type_id' => 3, 'type' => 'text', 'name' => 'method', 'label' => 'Method', 'required' => 1],
            ['type_id' => 3, 'type' => 'json', 'name' => 'additional_headers', 'label' => 'Additional Headers', 'required' => 0],
        ]);

        $this->tablePrefix('form_action')
            ->addLinkedColumn('form_id', 'form', 'id')
            ->addLinkedColumn('type_id', 'form_action_type', 'id')
            ->addColumn('enabled', 'boolean')
            ->addColumn('config', 'json')
            ->create();

        $this->tablePrefix('form_submission')
            ->addColumn('date', 'datetime')
            ->addLinkedColumn('form_id', 'form', 'id')
            ->create();

        $this->tablePrefix('form_submission_field')
            ->addLinkedColumn('submission_id', 'form_submission', 'id')
            ->addLinkedColumn('field_id', 'form_field', 'id')
            ->addColumn('value', 'text')
            ->create();

        $this->tablePrefix('form_submission_action')
            ->addColumn('date', 'datetime')
            ->addLinkedColumn('submission_id', 'form_submission', 'id')
            ->addLinkedColumn('action_id', 'form_action', 'id')
            ->addColumn('success', 'boolean')
            ->addColumn('output', 'text', ['null' => true])
            ->addColumn('result', 'string', ['null' => true])
            ->create();
    }
}
