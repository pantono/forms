<?php

namespace Pantono\Forms;

use Pantono\Forms\Repository\FormsRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Pantono\Hydrator\Hydrator;
use Pantono\Forms\Model\FormValidator;
use Pantono\Forms\Model\FormFieldType;
use Pantono\Forms\Model\Form;
use Pantono\Forms\Model\FormSubmission;
use Symfony\Component\HttpFoundation\ParameterBag;
use Pantono\Forms\Model\FormSubmissionField;
use Pantono\Forms\Exception\ValidatorControllerDoesNotExist;
use Pantono\Forms\Validator\AbstractValidator;
use Pantono\Contracts\Locator\LocatorInterface;
use Pantono\Forms\Event\PreFormSubmissionSaveEvent;
use Pantono\Forms\Event\PostFormSubmissionSaveEvent;
use Pantono\Forms\Actions\AbstractActionController;
use Pantono\Forms\Model\FormSubmissionAction;
use Pantono\Forms\Actions\ActionRunner;

class Forms
{
    private FormsRepository $repository;
    private Hydrator $hydrator;
    private EventDispatcherInterface $dispatcher;
    private LocatorInterface $locator;
    private ActionRunner $actionRunner;

    public function __construct(FormsRepository $repository, Hydrator $hydrator, EventDispatcherInterface $dispatcher, LocatorInterface $locator, ActionRunner $actionRunner)
    {
        $this->repository = $repository;
        $this->hydrator = $hydrator;
        $this->dispatcher = $dispatcher;
        $this->locator = $locator;
        $this->actionRunner = $actionRunner;
    }

    public function getFormById(int $id): ?Form
    {
        return $this->hydrator->lookupRecord(Form::class, $id);
    }

    public function getFieldTypeById(int $id): ?FormFieldType
    {
        return $this->hydrator->lookupRecord(FormFieldType::class, $id);
    }

    public function createSubmission(Form $form, array $data): FormSubmission
    {
        $params = new ParameterBag($data);
        $submission = new FormSubmission();
        $submission->setFormId($form->getId());
        $submission->setDate(new \DateTime);
        foreach ($form->getFields() as $field) {
            $formField = new FormSubmissionField();
            $formField->setField($field);
            if ($params->has($field->getName())) {
                $formField->setValue($params->get($field->getName()));
            }
            $submission->addField($formField);
        }
        return $submission;
    }

    public function saveSubmission(FormSubmission $submission): void
    {
        $previous = $submission->getId() ? $this->hydrator->lookupRecord(FormSubmission::class, $submission->getId()) : null;
        $event = new PreFormSubmissionSaveEvent();
        $event->setCurrent($submission);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);

        $this->repository->saveModel($submission);

        $event = new PostFormSubmissionSaveEvent();
        $event->setCurrent($submission);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);
    }

    public function createValidatorClass(FormValidator $formValidator): AbstractValidator
    {
        $controller = $formValidator->getController();
        if (!class_exists($controller)) {
            throw new ValidatorControllerDoesNotExist(
                sprintf('Controller %s does not exist', $controller)
            );
        }

        return $this->locator->getClassAutoWire($controller);
    }

    public function runActions(FormSubmission $submission): void
    {
        $form = $this->getFormById($submission->getFormId());
        if ($form) {
            foreach ($form->getActions() as $action) {
                $this->actionRunner->runAction($action, $submission);
            }
        }
    }
}
