<?php

namespace Pantono\Forms\Repository;

use Pantono\Database\Repository\DefaultRepository;
use Pantono\Forms\Filter\FormFilter;

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

    public function getByFilter(FormFilter $filter): array
    {
        $select = $this->getDb()->select('f.*')->from('form', 'f');

        if ($filter->getSearch()) {
            $select->whereParam('f.name LIKE ?', '%' . $filter->getSearch() . '%');
        }

        $this->applyLimit($select, $filter);
        return $this->getDb()->fetchAll($select);
    }
}
