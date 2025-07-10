<?php enum ActionInconsistancy
{
    case PROFESSIONAL;
    case INSTRUCTOR;
    case SCHOOL;
    case STUDENT;
    case CLASSROOM;
    case LUNCH;
    case STAGES;
    case SAGRES;

    function getRoute($id)
    {
        return match ($this) {
            ActionInconsistancy::PROFESSIONAL => '?r=professional/default/update&id=' . $id,
            ActionInconsistancy::INSTRUCTOR => '?r=instructor/update&id=' . $id,
            ActionInconsistancy::SCHOOL => '?r=school/update&id=' . $id,
            ActionInconsistancy::STUDENT => '?r=student/update&id=' . $id,
            ActionInconsistancy::CLASSROOM => '?r=classroom/update&id=' . $id,
            ActionInconsistancy::LUNCH => '?r=lunch/lunch/update&id=' . $id,
            ActionInconsistancy::STAGES => '?r=stages/default/update&id=' . $id,
            ActionInconsistancy::SAGRES => '?r=sagres/default/createorupdate',
        };
    }

    function byId($id)
    {
        return match ($id) {
            '1' => ActionInconsistancy::PROFESSIONAL,
            '2' => ActionInconsistancy::INSTRUCTOR,
            '3' => ActionInconsistancy::SCHOOL,
            '4' => ActionInconsistancy::STUDENT,
            '5' => ActionInconsistancy::CLASSROOM,
            '6' => ActionInconsistancy::LUNCH,
            '7' => ActionInconsistancy::STAGES,
            '8' => ActionInconsistancy::SAGRES,
        };
    }

    function getId()
    {
        return match ($this) {
            ActionInconsistancy::PROFESSIONAL => '1',
            ActionInconsistancy::INSTRUCTOR => '2',
            ActionInconsistancy::SCHOOL => '3',
            ActionInconsistancy::STUDENT => '4',
            ActionInconsistancy::CLASSROOM => '5',
            ActionInconsistancy::LUNCH => '6',
            ActionInconsistancy::STAGES => '7',
            ActionInconsistancy::SAGRES => '8',
        };
    }

} ?>