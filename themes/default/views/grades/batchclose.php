<?php

$this->setPageTitle('TAG - ' . Yii::t('default', 'Sagres'));

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/sagres.css');
?>

<div id="mainPage" class="main">
    <div class="row">
        <div class="column">
            <h1>Fechamento de Turma</h1>
        </div>
    </div>
    <div class="alert alert-error alert-error-export" style="display: none;"></div>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
        <br />
    <?php endif ?>

    <ul id="classrooms-list">

    </ul>
</div>

<script>

    function closeClassroom(classroomId) {
        $(`#${classroomId}`).append("Loading...");
        fetch(`?r=grades/ClassClosure&classroomId=${classroomId}`, { method: "POST" })
            .then(async (response) => {
                const parse = await response.json();
                if (parse.hasOwnProperty("success")) {
                    $(`#${classroomId}`).remove();
                }

            }).catch((reason) => console.error(reason));
    }

    (async function () {
        const response = await fetch("?r=grades/ClassClosureList");
        const classrooms = await response.json();
        classrooms.forEach(element => {
            $("#classrooms-list").append(`<li id=${element.id}><button name="aaa" onclick="closeClassroom(${element.id})">${element.name}</button></li>`)
        });

    })();

</script>
