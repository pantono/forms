<?php

namespace Pantono\Forms\Repository;

use Pantono\Database\Repository\DefaultRepository;
use Pantono\Forms\Filter\FormFilter;
use Doctrine\DBAL\ArrayParameterType;
use Pantono\Forms\Model\Form;

class FormsRepository extends DefaultRepository
{
    /**
     * @return array<int,mixed>
     */
    public function getAllFieldTypes(): array
    {
        return $this->selectAll($this->appendTablePrefix('form_field_type'));
    }

    /**
     * @return array<int,mixed>
     */
    public function getAllValidators(): array
    {
        return $this->selectAll($this->appendTablePrefix('form_validator'));
    }

    /**
     * @return array<int,mixed>
     */
    public function getByFilter(FormFilter $filter): array
    {
        $select = $this->getDb()->select('f.*')->from('form', 'f');

        if ($filter->getSearch()) {
            $select->whereParam('f.name LIKE ?', '%' . $filter->getSearch() . '%');
        }

        $this->applyLimit($select, $filter);
        return $this->getDb()->fetchAll($select);
    }

    public function saveForm(Form $form): void
    {
        $id = $this->insertOrUpdate($this->appendTablePrefix('form'), 'id', $form->getId(), $form->getAllData());
        if ($id) {
            $form->setId($id);
        }

        foreach ($form->getFields() as $field) {
            $field->setFormId($form->getId());
            $fieldId = $this->insertOrUpdate($this->appendTablePrefix('form_field'), 'id', $field->getId(), $field->getAllData());
            if ($fieldId) {
                $field->setId($fieldId);
            }
            $doneIds = [];
            foreach ($field->getValidators() as $validator) {
                $validatorId = $this->insertOrUpdate($this->appendTablePrefix('form_field_validator'), 'id', $validator->getId(), $validator->getAllData());
                if ($validatorId) {
                    $validator->setId($validatorId);
                }
                $doneIds[] = $validator->getId();
            }
            $qb = $this->getDb()->createQueryBuilder()->delete($this->appendTablePrefix('form_field_validator'))
                ->whereParam('field_id = ?', $fieldId);
            if (!empty($doneIds)) {
                $qb->where('id not in (:ids)')
                    ->setParameter('ids', $doneIds, ArrayParameterType::INTEGER);
            }
            $qb->executeQuery();
        }

        foreach ($form->getActions() as $action) {
            $action->setFormId($form->getId());
            $actionId = $this->insertOrUpdate($this->appendTablePrefix('form_action'), 'id', $action->getId(), $action->getAllData());
            if ($actionId) {
                $action->setId($actionId);
            }
        }
    }
}
