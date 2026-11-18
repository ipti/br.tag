<?php

/**
 * This is the model class for table "class_contents".
 *
 * The followings are the available columns in table 'class_contents':
 * @property integer $id
 * @property integer $schedule_fk
 * @property integer|null $course_class_fk id da aula do plano (sem FK: a aula pode ter sido apagada)
 * @property integer|null $course_plan_id id do plano (sem FK)
 * @property string|null $course_plan_name
 * @property integer|null $course_plan_users_id dono do plano
 * @property integer|null $course_plan_stage_id etapa do plano
 * @property integer|null $course_plan_discipline_id componente do plano
 * @property integer|null $class_order ordem da aula no plano
 * @property string|null $content
 * @property string|null $methodology
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $discipline_fk
 * @property integer $day
 * @property integer $month
 * @property integer $year
 * @property integer $classroom_fk
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property Schedule $scheduleFk
 * @property CourseClass|null $courseClassFk
 * @property ClassContentsAbility[] $abilities
 *
 * A aula ministrada guarda uma cópia da aula do plano (nome do plano, dono,
 * etapa, componente, ordem, conteúdo, metodologia e habilidades). Assim ela não
 * some se a aula ou o plano forem apagados. Enquanto a aula do plano existir,
 * a cópia acompanha as alterações do plano (syncFromCoursePlan).
 */
class ClassContents extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'class_contents';
    }

    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true,
                'timestampExpression' => new CDbExpression('CONVERT_TZ(NOW(), "+00:00", "-03:00")'),
            ]
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['schedule_fk', 'required'],
            ['schedule_fk, course_class_fk, day, month, year, classroom_fk, discipline_fk, course_plan_id, course_plan_users_id, course_plan_stage_id, course_plan_discipline_id, class_order', 'numerical', 'integerOnly' => true],
            ['fkid', 'length', 'max' => 40],
            ['course_plan_name', 'length', 'max' => 100],
            ['content, methodology, created_at, updated_at', 'safe'],
            ['id, schedule_fk, course_class_fk, fkid, created_at, updated_at, day, month, year, classroom_fk, discipline_fk', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'courseClassFk' => [self::BELONGS_TO, CourseClass::class, 'course_class_fk'],
            'abilities' => [self::HAS_MANY, ClassContentsAbility::class, 'class_contents_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'schedule_fk' => 'Schedule Fk',
            'course_class_fk' => 'Course Class Fk',
            'fkid' => 'Fkid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'day' => 'Day',
            'month' => 'Month',
            'year' => 'Year',
            'classroom_fk' => 'Classroom Fk',
            'discipline_fk' => 'Discipline Fk',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('schedule_fk', $this->schedule_fk);
        $criteria->compare('course_class_fk', $this->course_class_fk);
        $criteria->compare('fkid', $this->fkid, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('day', $this->day);
        $criteria->compare('month', $this->month);
        $criteria->compare('year', $this->year);
        $criteria->compare('classroom_fk', $this->classroom_fk);
        $criteria->compare('discipline_fk', $this->discipline_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ClassContents the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /** @var int[]|null habilidades a gravar depois do insert */
    private $abilityIdsToCopy = null;

    protected function beforeSave()
    {
        if ($this->isNewRecord && $this->course_plan_id === null && $this->course_class_fk !== null) {
            $this->fillFromCourseClass();
        }

        return parent::beforeSave();
    }

    protected function afterSave()
    {
        parent::afterSave();

        if ($this->abilityIdsToCopy !== null) {
            foreach (array_unique($this->abilityIdsToCopy) as $abilityId) {
                $ability = new ClassContentsAbility();
                $ability->class_contents_fk = $this->id;
                $ability->course_class_ability_fk = $abilityId;
                $ability->save();
            }
            $this->abilityIdsToCopy = null;
        }
    }

    /**
     * Copia para a aula ministrada os dados da aula do plano (course_class_fk).
     * Se a aula do plano já foi apagada, copia de outra aula ministrada que
     * aponte para ela.
     */
    public function fillFromCourseClass(): void
    {
        $courseClass = CourseClass::model()->with('coursePlanFk')->findByPk($this->course_class_fk);
        if ($courseClass !== null && $courseClass->coursePlanFk !== null) {
            $plan = $courseClass->coursePlanFk;
            $this->course_plan_id = $plan->id;
            $this->course_plan_name = $plan->name;
            $this->course_plan_users_id = $plan->users_fk;
            $this->course_plan_stage_id = $plan->modality_fk;
            $this->course_plan_discipline_id = $plan->discipline_fk;
            $this->class_order = $courseClass->order;
            $this->content = $courseClass->content;
            $this->methodology = $courseClass->methodology;
            $this->abilityIdsToCopy = Yii::app()->db->createCommand(
                'SELECT course_class_ability_fk FROM course_class_has_class_ability WHERE course_class_fk = :id'
            )->queryColumn([':id' => $courseClass->id]);
            return;
        }

        $previous = self::model()->find([
            'condition' => 'course_class_fk = :course_class_fk AND course_plan_id IS NOT NULL',
            'params' => [':course_class_fk' => $this->course_class_fk],
            'order' => 'id DESC',
        ]);
        if ($previous !== null) {
            $this->copyPlanFrom($previous);
        }
    }

    /**
     * Copia os dados do plano guardados em outra aula ministrada.
     */
    public function copyPlanFrom(ClassContents $other): void
    {
        $this->course_class_fk = $other->course_class_fk;
        $this->course_plan_id = $other->course_plan_id;
        $this->course_plan_name = $other->course_plan_name;
        $this->course_plan_users_id = $other->course_plan_users_id;
        $this->course_plan_stage_id = $other->course_plan_stage_id;
        $this->course_plan_discipline_id = $other->course_plan_discipline_id;
        $this->class_order = $other->class_order;
        $this->content = $other->content;
        $this->methodology = $other->methodology;
        $this->abilityIdsToCopy = Yii::app()->db->createCommand(
            'SELECT course_class_ability_fk FROM class_contents_ability WHERE class_contents_fk = :id'
        )->queryColumn([':id' => $other->id]);
    }

    /**
     * Atualiza a cópia das aulas ministradas cujas aulas ainda existem no plano.
     * Chamado depois de salvar o plano. Aulas apagadas do plano mantêm a cópia.
     */
    public static function syncFromCoursePlan(CoursePlan $coursePlan): void
    {
        $db = Yii::app()->db;
        $params = [':plan' => $coursePlan->id];

        $db->createCommand(
            'UPDATE class_contents cc
            JOIN course_class c ON c.id = cc.course_class_fk
            JOIN course_plan cp ON cp.id = c.course_plan_fk
            SET cc.course_plan_id = cp.id,
                cc.course_plan_name = cp.name,
                cc.course_plan_users_id = cp.users_fk,
                cc.course_plan_stage_id = cp.modality_fk,
                cc.course_plan_discipline_id = cp.discipline_fk,
                cc.class_order = c.`order`,
                cc.content = c.content,
                cc.methodology = c.methodology
            WHERE cp.id = :plan'
        )->execute($params);

        $db->createCommand(
            'DELETE cca FROM class_contents_ability cca
            JOIN class_contents cc ON cc.id = cca.class_contents_fk
            JOIN course_class c ON c.id = cc.course_class_fk
            WHERE c.course_plan_fk = :plan'
        )->execute($params);

        $db->createCommand(
            'INSERT IGNORE INTO class_contents_ability (class_contents_fk, course_class_ability_fk, created_at, updated_at)
            SELECT cc.id, h.course_class_ability_fk, NOW(), NOW()
            FROM class_contents cc
            JOIN course_class c ON c.id = cc.course_class_fk
            JOIN course_class_has_class_ability h ON h.course_class_fk = c.id
            WHERE c.course_plan_fk = :plan'
        )->execute($params);
    }

    /**
     * Grava os conteúdos escolhidos para um horário: apaga os do professor que
     * saíram e cria só os novos. Os que continuam marcados não são recriados,
     * então mantêm a cópia do plano mesmo que a aula do plano já não exista.
     *
     * @param int[] $courseClassIds
     */
    public static function saveForSchedule(Schedule $schedule, array $courseClassIds, $userId): void
    {
        $courseClassIds = array_values(array_unique(array_filter(array_map('intval', $courseClassIds))));

        $existing = self::model()->findAll(
            'schedule_fk = :schedule AND course_plan_users_id = :user',
            [':schedule' => $schedule->id, ':user' => $userId]
        );
        foreach ($existing as $classContent) {
            if (!in_array((int) $classContent->course_class_fk, $courseClassIds, true)) {
                $classContent->delete();
            }
        }

        foreach ($courseClassIds as $courseClassId) {
            $alreadySaved = self::model()->exists(
                'schedule_fk = :schedule AND course_class_fk = :course_class',
                [':schedule' => $schedule->id, ':course_class' => $courseClassId]
            );
            if ($alreadySaved) {
                continue;
            }

            $classContent = new ClassContents();
            $classContent->schedule_fk = $schedule->id;
            $classContent->course_class_fk = $courseClassId;
            $classContent->day = $schedule->day;
            $classContent->month = $schedule->month;
            $classContent->year = $schedule->year;
            $classContent->classroom_fk = $schedule->classroom_fk;
            $classContent->discipline_fk = $schedule->discipline_fk;
            $classContent->save();
        }
    }

    /**
     * Acrescenta à lista de aulas do plano (formato das telas: id, cpname, edid,
     * edname, order, content, cpid) as aulas ministradas cuja aula do plano já
     * foi apagada, para que continuem aparecendo marcadas na tela.
     *
     * @param array $courseClasses aulas do plano já listadas
     * @param int[] $courseClassIds course_class_fk das aulas ministradas
     * @param int|null $userId só as aulas desse professor (dono do plano)
     */
    public static function appendMissingCourseClasses(array $courseClasses, array $courseClassIds, $userId = null): array
    {
        $listed = array_map('intval', array_column($courseClasses, 'id'));
        $missing = array_values(array_diff(array_unique(array_map('intval', $courseClassIds)), $listed));
        if (empty($missing)) {
            return $courseClasses;
        }

        $conditions = ['and', ['in', 'cc.course_class_fk', $missing], 'cc.course_plan_id IS NOT NULL'];
        $params = [];
        if ($userId !== null) {
            $conditions[] = 'cc.course_plan_users_id = :user';
            $params[':user'] = $userId;
        }

        $rows = Yii::app()->db->createCommand()
            ->select('cc.course_class_fk AS id, MAX(cc.course_plan_name) AS cpname, MAX(ed.id) AS edid, MAX(ed.name) AS edname, MAX(cc.class_order) AS `order`, MAX(cc.content) AS content, MAX(cc.course_plan_id) AS cpid')
            ->from('class_contents cc')
            ->leftJoin('edcenso_discipline ed', 'ed.id = cc.course_plan_discipline_id')
            ->where($conditions, $params)
            ->group('cc.course_class_fk')
            ->queryAll();

        return array_merge($courseClasses, $rows);
    }

    /**
     * Habilidades da aula ministrada (cópia), no formato [code, description].
     */
    public function getAbilityList(): array
    {
        return Yii::app()->db->createCommand(
            'SELECT a.code, a.description
            FROM class_contents_ability cca
            JOIN course_class_abilities a ON a.id = cca.course_class_ability_fk
            WHERE cca.class_contents_fk = :id
            ORDER BY a.code'
        )->queryAll(true, [':id' => $this->id]);
    }

    public function getTotalClassesByMonth($classroomId, $month, $year, $disciplineId)
    {
        if (!$disciplineId) {
            return Yii::app()->db->createCommand(
                'select count(*) from schedule sc
                where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
                and sc.unavailable = 0'
            )
                ->bindParam(':classroom', $classroomId)
                ->bindParam(':month', $month)
                ->bindParam(':year', $year)
                ->queryScalar();
        }
        return Yii::app()->db->createCommand(
            'select count(*) from schedule sc
            where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
            and sc.discipline_fk = :discipline and sc.unavailable = 0'
        )
            ->bindParam(':classroom', $classroomId)
            ->bindParam(':month', $month)
            ->bindParam(':year', $year)
            ->bindParam(':discipline', $disciplineId)
            ->queryScalar();
    }

    public function getTotalClassesMinorStage($classroomId): int
    {
        return Yii::app()->db->createCommand(
            "select COUNT(DISTINCT CONCAT(sc.`month`, '-', sc.`day`)) from schedule sc
                where sc.classroom_fk = :classroom
                and sc.unavailable = 0"
        )
            ->bindParam(':classroom', $classroomId)
            ->queryScalar();
    }

    public function getTotalClassesByClassroomAndDiscipline($classroomId, $disciplineId): int
    {
        return Yii::app()->db->createCommand(
            'select count(*) from class_contents cc
            join schedule sc on sc.id = cc.schedule_fk
            where sc.classroom_fk = :classroom
            and sc.discipline_fk = :discipline and sc.unavailable = 0'
        )
            ->bindParam(':classroom', $classroomId)
            ->bindParam(':discipline', $disciplineId)
            ->queryScalar();
    }

    /**
     * Total de aulas ministradas do mês: horários do quadro (não indisponíveis)
     * que têm conteúdo registrado no dia. Os conteúdos são gravados por dia, no
     * primeiro horário. Contar linhas de class_contents somava uma aula a mais
     * para cada conteúdo extra do dia e não contava os outros horários do mesmo
     * componente no dia.
     */
    public function getTotalClassContentsByMonth($classroomId, $month, $year, $disciplineId)
    {
        return self::countSchedulesWithContent($classroomId, $month, $year, $disciplineId);
    }

    public static function countSchedulesWithContent($classroomId, $month, $year, $disciplineId): int
    {
        // Sem componente (educação infantil e anos iniciais), vale qualquer conteúdo do dia
        $disciplineFilter = $disciplineId ? 'AND sc.discipline_fk = :discipline' : '';
        $disciplineMatch = $disciplineId ? 'AND cc.discipline_fk = sc.discipline_fk' : '';

        $params = [':classroom' => $classroomId, ':month' => $month, ':year' => $year];
        if ($disciplineId) {
            $params[':discipline'] = $disciplineId;
        }

        return (int) Yii::app()->db->createCommand(
            "SELECT COUNT(*) FROM schedule sc
            WHERE sc.year = :year AND sc.month = :month AND sc.classroom_fk = :classroom
                AND sc.unavailable = 0 {$disciplineFilter}
                AND (
                    EXISTS (SELECT 1 FROM class_contents cc WHERE cc.schedule_fk = sc.id)
                    OR EXISTS (
                        SELECT 1 FROM class_contents cc
                        WHERE cc.classroom_fk = sc.classroom_fk AND cc.year = sc.year
                            AND cc.month = sc.month AND cc.day = sc.day {$disciplineMatch}
                    )
                )"
        )->queryScalar($params);
    }

    public function buildClassContents($schedules, $students)
    {
        $classContents = [];
        foreach ($schedules as $schedule) {
            $scheduleDate = date('Y-m-d', mktime(0, 0, 0, $schedule->month, $schedule->day, $schedule->year));
            $classContents[$schedule->day]['available'] = date('Y-m-d') >= $scheduleDate;
            $classContents[$schedule->day]['diary'] = $schedule->diary !== null ? $schedule->diary : '';
            $classContents[$schedule->day]['students'] = [];

            $studentArray = StudentEnrollment::model()->getStudentClassAnottations($schedule, $students);
            array_push($classContents[$schedule->day]['students'], $studentArray);

            $courseClasses = [];
            foreach ($schedule->classContents as $classContent) {
                if (TagUtils::isInstructor() && $classContent->course_plan_users_id != Yii::app()->user->loginInfos->id) {
                    continue;
                }

                if (!isset($classContents[$schedule->day]['contents'])) {
                    $classContents[$schedule->day]['contents'] = [];
                }
                // Cópia do plano guardada na aula ministrada: vale mesmo se a aula do plano foi apagada
                $courseClasses['order'] = $classContent->class_order;
                $courseClasses['name'] = $classContent->course_plan_name;
                $courseClasses['content'] = $classContent->content;
                $courseClasses['abilities'] = $classContent->getAbilityList();

                array_push($classContents[$schedule->day]['contents'], $courseClasses);
            }
        }

        return $classContents;
    }
}
