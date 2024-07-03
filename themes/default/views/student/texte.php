Observe esse código abaixo:


$commandMaxId = Yii::app()->db->createCommand("
        SELECT MAX(id) AS max_id
        FROM student_enrollment
        WHERE student_fk = :student_fk AND status = 1
    ");
    $commandMaxId->bindValue(':student_fk', $enrollment_id);
    $maxId = $commandMaxId->queryScalar();


    CVarDumper::dump($maxId, 10, true);
    exit();

Como fazer para que ao inves de retornar apneas um id ele retorne todos, ou seja, preciso que ele faça esse comando e retorne todos os id
