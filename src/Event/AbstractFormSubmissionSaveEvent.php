<?php

namespace Pantono\Forms\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Forms\Model\FormSubmission;

class AbstractFormSubmissionSaveEvent extends Event
{
    private FormSubmission $current;
    private ?FormSubmission $previous = null;

    public function getCurrent(): FormSubmission
    {
        return $this->current;
    }

    public function setCurrent(FormSubmission $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?FormSubmission
    {
        return $this->previous;
    }

    public function setPrevious(?FormSubmission $previous): void
    {
        $this->previous = $previous;
    }
}
