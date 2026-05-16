<?php

namespace Pantono\Forms\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Forms\Model\Form;

class AbstractFormSaveEvent extends Event
{
    private Form $current;
    private ?Form $previous = null;

    public function getCurrent(): Form
    {
        return $this->current;
    }

    public function setCurrent(Form $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?Form
    {
        return $this->previous;
    }

    public function setPrevious(?Form $previous): void
    {
        $this->previous = $previous;
    }
}
