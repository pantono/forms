<?php

namespace Pantono\Forms\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pantono\Forms\Event\PostFormSubmissionSaveEvent;
use Pantono\Forms\Forms;

class FormSubmissionActionsListener implements EventSubscriberInterface
{
    private Forms $forms;

    public function __construct(Forms $forms)
    {
        $this->forms = $forms;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostFormSubmissionSaveEvent::class => [
                ['runActions', 255]
            ]
        ];
    }

    public function runActions(PostFormSubmissionSaveEvent $event): void
    {
        if (!$event->getPrevious()) {
            $this->forms->runActions($event->getCurrent());
        }
    }
}
