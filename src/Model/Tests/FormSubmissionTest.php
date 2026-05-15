<?php

namespace Pantono\Forms\Model\Tests;

use PHPUnit\Framework\TestCase;
use Pantono\Forms\Model\FormSubmission;
use Pantono\Forms\Model\FormSubmissionField;
use Pantono\Forms\Model\FormField;

class FormSubmissionTest extends TestCase
{
    public function testGetFieldArray(): void
    {
        $submission = new FormSubmission();
        
        $field1 = new FormField();
        $field1->setName('first_name');
        $submissionField1 = new FormSubmissionField();
        $submissionField1->setField($field1);
        $submissionField1->setValue('John');
        
        $field2 = new FormField();
        $field2->setName('last_name');
        $submissionField2 = new FormSubmissionField();
        $submissionField2->setField($field2);
        $submissionField2->setValue('Doe');
        
        $submission->addField($submissionField1);
        $submission->addField($submissionField2);
        
        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];
        
        $this->assertEquals($expected, $submission->getFieldArray());
    }
}
