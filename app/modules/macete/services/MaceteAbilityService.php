<?php

class MaceteAbilityService
{
    public function search(string $term): array
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'ability';
        $criteria->addCondition('ability.code IS NOT NULL');
        $criteria->addCondition('(ability.code LIKE :term OR ability.description LIKE :term)');
        $criteria->params = [':term' => '%' . $term . '%'];
        $criteria->order = 'ability.code ASC';
        $criteria->limit = 30;

        $abilities = CourseClassAbilities::model()->findAll($criteria);
        $result = [];

        foreach ($abilities as $ability) {
            $result[] = $this->formatAbility($ability);
        }

        return $result;
    }

    public function initialStructure(?int $disciplineId): array
    {
        if ($disciplineId === null) {
            return ['selectTitle' => '', 'options' => []];
        }

        $criteria = new CDbCriteria();
        $criteria->alias = 'ability';
        $criteria->condition = 'ability.edcenso_discipline_fk = :discipline AND ability.parent_fk IS NULL';
        $criteria->params = [':discipline' => $disciplineId];
        $criteria->order = 'ability.description ASC';

        $abilities = CourseClassAbilities::model()->findAll($criteria);

        return $this->formatStructure($abilities);
    }

    public function nextStructure(int $parentId): array
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'parent_fk = :parent_fk';
        $criteria->params = [':parent_fk' => $parentId];
        $criteria->order = 'description ASC';

        $abilities = CourseClassAbilities::model()->findAll($criteria);

        return $this->formatStructure($abilities);
    }

    public function getByIds(array $ids): array
    {
        $ids = $this->normalizeIds($ids);
        if (empty($ids)) {
            return [];
        }

        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $ids);
        $criteria->order = 'code ASC';

        return CourseClassAbilities::model()->findAll($criteria);
    }

    public function normalizeIds(array $ids): array
    {
        $normalized = [];
        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $normalized[$id] = $id;
            }
        }

        return array_values($normalized);
    }

    private function formatStructure(array $abilities): array
    {
        $result = [
            'selectTitle' => '',
            'options' => [],
        ];

        foreach ($abilities as $index => $ability) {
            if ($index === 0) {
                $result['selectTitle'] = (string) $ability->type;
            }
            $result['options'][] = $this->formatAbility($ability);
        }

        return $result;
    }

    private function formatAbility(CourseClassAbilities $ability): array
    {
        return [
            'id' => (int) $ability->id,
            'code' => $ability->code,
            'description' => $ability->description,
        ];
    }
}

