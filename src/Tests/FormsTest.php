<?php

namespace Pantono\Forms\Tests;

use PHPUnit\Framework\TestCase;
use Pantono\Forms\Forms;
use Pantono\Forms\Repository\FormsRepository;
use Pantono\Hydrator\Hydrator;
use Psr\EventDispatcher\EventDispatcherInterface;
use Pantono\Contracts\Locator\LocatorInterface;
use Pantono\Forms\Model\Form;
use Pantono\Forms\Model\FormField;
use Pantono\Forms\Model\FormSubmission;
use Pantono\Forms\Actions\ActionRunner;

class FormsTest extends TestCase
{
    private function createForms(
        ?FormsRepository $repository = null,
        ?Hydrator $hydrator = null,
        ?EventDispatcherInterface $dispatcher = null,
        ?LocatorInterface $locator = null,
        ?ActionRunner $actionRunner = null
    ): Forms {
        return new Forms(
            $repository ?? $this->createStub(FormsRepository::class),
            $hydrator ?? $this->createStub(Hydrator::class),
            $dispatcher ?? $this->createStub(EventDispatcherInterface::class),
            $locator ?? $this->createStub(LocatorInterface::class),
            $actionRunner ?? $this->createStub(ActionRunner::class)
        );
    }

    public function testCreateSubmission(): void
    {
        $forms = $this->createForms();
        $form = new Form();
        $form->setId(1);
        
        $field1 = new FormField();
        $field1->setName('name');
        
        $field2 = new FormField();
        $field2->setName('email');
        
        $form->setFields([$field1, $field2]);
        
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'extra' => 'ignored'
        ];
        
        $submission = $forms->createSubmission($form, $data);
        
        $this->assertEquals(1, $submission->getFormId());
        $this->assertCount(2, $submission->getFields());
        
        $fieldArray = $submission->getFieldArray();
        $this->assertEquals('John Doe', $fieldArray['name']);
        $this->assertEquals('john@example.com', $fieldArray['email']);
        $this->assertArrayNotHasKey('extra', $fieldArray);
    }

    public function testSaveSubmission(): void
    {
        $repository = $this->createMock(FormsRepository::class);
        $hydrator = $this->createMock(Hydrator::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $forms = $this->createForms($repository, $hydrator, $dispatcher);

        $submission = new FormSubmission();
        $submission->setId(123);
        
        $hydrator->expects($this->once())
            ->method('lookupRecord')
            ->with(FormSubmission::class, 123)
            ->willReturn(new FormSubmission());
            
        $dispatcher->expects($this->exactly(2))
            ->method('dispatch');
            
        $repository->expects($this->once())
            ->method('saveModel')
            ->with($submission);
            
        $forms->saveSubmission($submission);
    }

    public function testRunActions(): void
    {
        $hydrator = $this->createMock(Hydrator::class);
        $actionRunner = $this->createMock(ActionRunner::class);
        $forms = $this->createForms(null, $hydrator, null, null, $actionRunner);

        $submission = new FormSubmission();
        $submission->setFormId(1);
        
        $form = new Form();
        $action1 = $this->createStub(\Pantono\Forms\Model\FormAction::class);
        $action2 = $this->createStub(\Pantono\Forms\Model\FormAction::class);
        $form->setActions([$action1, $action2]);
        
        $hydrator->expects($this->once())
            ->method('lookupRecord')
            ->with(Form::class, 1)
            ->willReturn($form);
            
        $actionRunner->expects($this->exactly(2))
            ->method('runAction');
            
        $forms->runActions($submission);
    }
}
