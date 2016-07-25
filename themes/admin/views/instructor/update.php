<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update InstructorIdentification'));
    $title = Yii::t('default', 'Update InstructorIdentification');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new InstructorIdentification'),
            'url' => array('create'),
            'description' => Yii::t('default', 'This action create a new InstructorIdentification')),
        array('label' => Yii::t('default', 'List InstructorIdentification'),
            'url' => array('index'),
            'description' => Yii::t('default', 'This action list all Instructor Identifications, you can search, delete and update')),
    );
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php
            echo $this->renderPartial('_form', array('modelInstructorIdentification' => $modelInstructorIdentification,
                'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress
            , 'modelInstructorVariableData' => $modelInstructorVariableData,
                'title' => $title, 'error' => $error));
            ?>        </div>
        <div class="columntwo">
        </div>
    </div>
</div>

<script>

    window.onload = function () {
        $(".select2-container").width("250");
        $("input, textarea, select").attr('disabled', true);
        $(".buttons").remove();
        $(".heading-mosaic").html("Atualizar Professor");
        $("#tab-instructor-identify, #tab-instructor-address, #tab-instructor-data").click(function(){
            $("input, textarea, select").attr('disabled', true);
        });
    }
</script>
