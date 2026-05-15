<?php

namespace Pantono\Forms\Actions;

use Pantono\Forms\Model\FormSubmission;
use Pantono\Forms\Model\FormSubmissionAction;
use Pantono\Forms\Model\FormAction;
use Pantono\Contracts\Locator\LocatorInterface;
use Pantono\Forms\Repository\FormsRepository;

class ActionRunner
{
    private LocatorInterface $locator;
    private FormsRepository $repository;

    public function __construct(LocatorInterface $locator, FormsRepository $repository)
    {
        $this->locator = $locator;
        $this->repository = $repository;
    }

    public function runAction(FormAction $action, FormSubmission $submission): void
    {
        if (!$action->isEnabled()) {
            return;
        }
        $controllerClass = $action->getActionType()->getController();
        if (!class_exists($controllerClass)) {
            return;
        }
        $controller = $this->locator->getClassAutoWire($controllerClass);
        if (!$controller instanceof AbstractActionController) {
            throw new \RuntimeException(sprintf('Controller %s is not a valid action controller. Must extend %s', $controllerClass, AbstractActionController::class));
        }

        $actionResult = new FormSubmissionAction();
        $actionResult->setAction($action);
        $actionResult->setSubmissionId($submission->getId());
        $actionResult->setDate(new \DateTime);
        try {
            $result = $controller->execute($submission, $action->getConfig());
            $actionResult->setSuccess(true);
            $actionResult->setResult($result);
        } catch (\Exception $e) {
            $actionResult->setSuccess(false);
            $actionResult->setResult($e->getMessage());
        }
        $this->repository->saveModel($actionResult);
    }
}
