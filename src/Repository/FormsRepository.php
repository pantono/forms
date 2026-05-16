<?php

namespace Pantono\Forms\Repository;

use Pantono\Database\Repository\DefaultRepository;

class FormsRepository extends DefaultRepository
{
    public function getAllFieldTypes(): array
    {
        return $this->selectAll($this->appendTablePrefix('form_field_type'));
    }

    public function getAllValidators(): array
    {
        return $this->selectAll($this->appendTablePrefix('form_validator'));
    }
}
