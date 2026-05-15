<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;
use Pantono\Forms\Validator\MinimumLengthValidator;
use Pantono\Forms\Validator\MaximumLengthValidator;
use Pantono\Forms\Validator\RegularExpression;
use Pantono\Forms\Validator\MinimumNumberValidator;
use Pantono\Forms\Validator\MaximumNumberValidator;
use Pantono\Forms\Validator\InListValidator;

final class FormsMigration extends BasePantonoMigration
{
    public function change(): void
    {
        $this->table('form_validator')
            ->addColumn('name', 'string')
            ->addColumn('controller', 'string')
            ->create();

        $this->insertOnCreate('form_validator', [
            ['id' => 1, 'name' => 'Minimum Length', 'controller' => MinimumLengthValidator::class],
            ['id' => 2, 'name' => 'Maximum Length', 'controller' => MaximumLengthValidator::class],
            ['id' => 3, 'name' => 'Regular Expression', 'controller' => RegularExpression::class],
            ['id' => 4, 'name' => 'Minimum Number', 'controller' => MinimumNumberValidator::class],
            ['id' => 5, 'name' => 'Maximum Number', 'controller' => MaximumNumberValidator::class],
            ['id' => 6, 'name' => 'In List', 'controller' => InListValidator::class],
        ]);

        $this->table('form_field_type')
            ->addColumn('name', 'string')
            ->addColumn('label', 'string')
            ->create();

        $this->insertOnCreate('form_field_type', [
            ['id' => 1, 'name' => 'text', 'label' => 'Text'],
            ['id' => 2, 'name' => 'number', 'label' => 'Number'],
            ['id' => 3, 'name' => 'select', 'label' => 'Select'],
            ['id' => 4, 'name' => 'checkbox', 'label' => 'Checkbox'],
            ['id' => 5, 'name' => 'textarea', 'label' => 'Textarea'],
        ]);

        $this->table('form_field_type_validator', ['id' => false])
            ->addLinkedColumn('field_type_id', 'form_field_type', 'id')
            ->addLinkedColumn('validator_id', 'form_validator', 'id')
            ->create();
        if ($this->isMigratingUp()) {
            $this->table('form_field_type_validator')->insert([
                ['field_type_id' => 1, 'validator_id' => 1],
                ['field_type_id' => 1, 'validator_id' => 2],
                ['field_type_id' => 1, 'validator_id' => 3],
            ]);
        }

        $this->table('form')
            ->addColumn('name', 'string')
            ->addColumn('intro_copy', 'text')
            ->addColumn('success_message', 'string', ['null' => true])
            ->addColumn('failure_message', 'string', ['null' => true])
            ->create();

        $this->table('form_field')
            ->addLinkedColumn('form_id', 'form', 'id')
            ->addLinkedColumn('field_type_id', 'form_field_type', 'id')
            ->addColumn('name', 'string')
            ->addColumn('label', 'string')
            ->addColumn('required', 'boolean')
            ->addColumn('config', 'json')
            ->create();

        $this->table('form_field_validator')
            ->addLinkedColumn('field_id', 'form_field', 'id')
            ->addLinkedColumn('validator_id', 'form_validator', 'id')
            ->addColumn('config', 'json')
            ->create();

        $this->table('form_action_type')
            ->addColumn('name', 'string')
            ->addColumn('required_config', 'json')
            ->addColumn('controller', 'string')
            ->create();

        $this->table('form_action')
            ->addLinkedColumn('form_id', 'form', 'id')
            ->addLinkedColumn('type_id', 'form_action_type', 'id')
            ->addColumn('enabled', 'boolean')
            ->addColumn('config', 'json')
            ->create();

        $this->table('form_submission')
            ->addColumn('date', 'datetime')
            ->addLinkedColumn('form_id', 'form', 'id')
            ->create();

        $this->table('form_submission_field')
            ->addLinkedColumn('submission_id', 'form_submission', 'id')
            ->addLinkedColumn('field_id', 'form_field', 'id')
            ->addColumn('value', 'text')
            ->create();

        $this->table('form_submission_action')
            ->addColumn('date', 'datetime')
            ->addLinkedColumn('submission_id', 'form_submission', 'id')
            ->addLinkedColumn('action_id', 'form_action', 'id')
            ->addColumn('success', 'boolean')
            ->addColumn('output', 'text', ['null' => true])
            ->addColumn('result', 'string', ['null' => true])
            ->create();
    }
}
