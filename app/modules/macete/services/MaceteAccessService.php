<?php

class MaceteAccessService
{
    public function requireLessonPlanFeature(): void
    {
        $this->requireFeature(TFeature::FEAT_DIARY_LESSON_PLAN);
    }

    public function requireLessonRecordFeature(): void
    {
        $this->requireFeature(TFeature::FEAT_DIARY_CLASSES);
    }

    public function requireMaceteFeature(): void
    {
        if (
            !Yii::app()->features->isEnable(TFeature::FEAT_DIARY_LESSON_PLAN)
            && !Yii::app()->features->isEnable(TFeature::FEAT_DIARY_CLASSES)
        ) {
            throw new CHttpException(403, 'Você não tem permissão para acessar esta funcionalidade.');
        }
    }

    public function applyPlanScope(CDbCriteria $criteria): void
    {
        $criteria->addCondition('school_inep_fk = :macete_school');
        $criteria->addCondition('school_year = :macete_school_year');
        $criteria->params[':macete_school'] = Yii::app()->user->school;
        $criteria->params[':macete_school_year'] = Yii::app()->user->year;

        if (TagUtils::isInstructor()) {
            $criteria->addCondition('users_fk = :macete_user');
            $criteria->params[':macete_user'] = $this->currentUserId();
        }
    }

    public function applyRecordScope(CDbCriteria $criteria): void
    {
        $criteria->addCondition('school_inep_fk = :macete_school');
        $criteria->addCondition('lesson_date >= :macete_year_start');
        $criteria->addCondition('lesson_date < :macete_next_year_start');
        $criteria->params[':macete_school'] = Yii::app()->user->school;
        $criteria->params[':macete_year_start'] = Yii::app()->user->year . '-01-01';
        $criteria->params[':macete_next_year_start'] = ((int) Yii::app()->user->year + 1) . '-01-01';

        if (TagUtils::isInstructor()) {
            $criteria->addCondition('users_fk = :macete_user');
            $criteria->params[':macete_user'] = $this->currentUserId();
        }
    }

    public function findPlan(int $id): ?MaceteLessonPlan
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('id = :macete_plan_id');
        $criteria->params[':macete_plan_id'] = $id;
        $this->applyPlanScope($criteria);

        return MaceteLessonPlan::model()->find($criteria);
    }

    public function findRecord(int $id): ?MaceteLessonRecord
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('id = :macete_record_id');
        $criteria->params[':macete_record_id'] = $id;
        $this->applyRecordScope($criteria);

        return MaceteLessonRecord::model()->find($criteria);
    }

    public function findClassroom(int $id): ?Classroom
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('id = :macete_classroom_id');
        $criteria->addCondition('school_inep_fk = :macete_school');
        $criteria->addCondition('school_year = :macete_school_year');
        $criteria->params = [
            ':macete_classroom_id' => $id,
            ':macete_school' => Yii::app()->user->school,
            ':macete_school_year' => Yii::app()->user->year,
        ];

        return Classroom::model()->find($criteria);
    }

    public function currentUserId(): int
    {
        return (int) Yii::app()->user->loginInfos->id;
    }

    private function requireFeature(TFeature $feature): void
    {
        if (!Yii::app()->features->isEnable($feature)) {
            throw new CHttpException(403, 'Você não tem permissão para acessar esta funcionalidade.');
        }
    }
}
