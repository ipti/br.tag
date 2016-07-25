<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update SchoolIdentification'));
    $title = Yii::t('default', 'Update SchoolIdentification');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new SchoolIdentification'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new SchoolIdentification')),
        array('label' => Yii::t('default', 'List SchoolIdentification'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all School Identifications, you can search, delete and update')),
    );
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php
            echo $this->renderPartial('_form', array(
                'modelSchoolIdentification' => $modelSchoolIdentification,
                'modelSchoolStructure' => $modelSchoolStructure,
                'title' => $title
            ));
            ?>
        </div>
        <div class="columntwo">
        </div>
    </div>
</div>


<script>

    window.onload = function () {
        $(".select2-container").width("250");
        $("input, textarea, select").attr('disabled', true);
        $("#tab-school-identify, #tab-school-structure, #tab-school-equipment, #tab-student-education").click(function(){
            $("input, textarea, select").attr('disabled', true);
        });
        $(".heading-mosaic").html("Atualizar Escola");
        $(".buttons").remove()
    }

</script>
