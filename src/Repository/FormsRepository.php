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
        $formId = $form->getId();

        $doneFieldIds = [];
        foreach ($form->getFields() as $field) {
            $field->setFormId($formId);
            $fieldId = $this->insertOrUpdate($this->appendTablePrefix('form_field'), 'id', $field->getId(), $field->getAllData());
            if ($fieldId) {
                $field->setId($fieldId);
            }
            $fieldId = $field->getId();
            if ($fieldId === null) {
                throw new \RuntimeException('Unable to save form field without an id');
            }
            $doneFieldIds[] = $fieldId;
            $doneIds = [];
            foreach ($field->getValidators() as $validator) {
                $validator->setFieldId($fieldId);
                $validatorId = $this->insertOrUpdate($this->appendTablePrefix('form_field_validator'), 'id', $validator->getId(), $validator->getAllData());
                if ($validatorId) {
                    $validator->setId($validatorId);
                }
                $doneIds[] = $validator->getId();
            }
            $qb = $this->getDb()->createQueryBuilder()->delete($this->appendTablePrefix('form_field_validator'))
                ->whereParam('field_id = ?', $fieldId);
            if (!empty($doneIds)) {
                $qb->andWhere('id not in (:ids)')
                    ->setParameter('ids', $doneIds, ArrayParameterType::INTEGER);
            }
            $qb->executeQuery();
        }
        $fieldTable = $this->appendTablePrefix('form_field');
        $validatorTable = $this->appendTablePrefix('form_field_validator');
        $staleFieldCondition = sprintf(
            'field_id in (select id from %s where form_id = :form_id)',
            $fieldTable
        );
        $fieldQb = $this->getDb()->createQueryBuilder()->delete($fieldTable)
            ->whereParam('form_id = ?', $formId);
        $validatorQb = $this->getDb()->createQueryBuilder()->delete($validatorTable)
            ->where($staleFieldCondition)
            ->setParameter('form_id', $formId);
        if (!empty($doneFieldIds)) {
            $fieldQb->andWhere('id not in (:ids)')
                ->setParameter('ids', $doneFieldIds, ArrayParameterType::INTEGER);
            $validatorQb->andWhere('field_id not in (:ids)')
                ->setParameter('ids', $doneFieldIds, ArrayParameterType::INTEGER);
        }
        $validatorQb->executeQuery();
        $fieldQb->executeQuery();

        $doneActionIds = [];
        foreach ($form->getActions() as $action) {
            $action->setFormId($formId);
            $actionId = $this->insertOrUpdate($this->appendTablePrefix('form_action'), 'id', $action->getId(), $action->getAllData());
            if ($actionId) {
                $action->setId($actionId);
            }
            $doneActionIds[] = $action->getId();
        }
        $actionQb = $this->getDb()->createQueryBuilder()->delete($this->appendTablePrefix('form_action'))
            ->andWhere('form_id = :form_id')
            ->setParameter('form_id', $formId);
        if (!empty($doneActionIds)) {
            $actionQb->andWhere('id not in (:ids)')
                ->setParameter('ids', $doneActionIds, ArrayParameterType::INTEGER);
        }
        $actionQb->executeQuery();
    }
}
