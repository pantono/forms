<?php

namespace Pantono\Forms\Actions;

use Pantono\Forms\Model\FormSubmission;

abstract class AbstractActionController
{
    abstract public function execute(FormSubmission $formSubmission, array $config = []): string;
}
