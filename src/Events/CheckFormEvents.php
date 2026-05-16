<?php

namespace Pantono\Forms\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pantono\Forms\Event\PreFormSaveEvent;
use Pantono\Forms\Exception\FormSaveValidationException;

class CheckFormEvents implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PreFormSaveEvent::class => [
                ['checkForm', 255]
            ]
        ];
    }


    public function checkForm(PreFormSaveEvent $event): void
    {
        $form = $event->getCurrent();

        $errors = [];
        foreach ($form->getActions() as $action) {
            $config = $action->getConfig();
            $type = $action->getActionType();
            foreach ($type->getFields() as $field) {
                $value = $config[$field->getName()] ?? null;
                if ($field->isRequired() && !$value) {
                    $errors[] = sprintf('Field %s is required for action %s', $field->getName(), $action->getActionType()->getName());
                }
            }
        }

        if (!empty($errors)) {
            throw new FormSaveValidationException($errors, 'Form has validation error');
        }
    }
}
