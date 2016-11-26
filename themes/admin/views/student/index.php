<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Student Identifications'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new StudentIdentification'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new StudentIdentification')),
    );
    ?>

    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Student Identifications') ?></h3>


        </div>
    </div>

    <div class="innerLR">
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
            <?php
            if (isset($buttons))
                echo $buttons;
            ?>
            <br/>
        <?php endif ?>
        <div class="widget">
            <div class="widget-body">
                <?php
                //<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i>Ok</i></button>
                //@done S1 - 05 - Tirar borda esquerda e direita do filtro por nome dos alunos
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $filter->search(),
                    'enablePagination' => true,
                    'filter' => $filter,
                    'selectableRows' => 1,
                    'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->name,yii::app()->createUrl("student/update",array("id"=>$data->hash)))',
                        ),
                        /*array(
                            'header' => '',
                            'value' => '0+$data->documentsFk->received_cc+$data->documentsFk->received_address+$data->documentsFk->received_photo'
                            . '+$data->documentsFk->received_nis+$data->documentsFk->received_history'
                            . '+$data->documentsFk->received_responsable_rg+$data->documentsFk->received_responsable_cpf."/7"',
                            'htmlOptions' => array('width' => '5px')
                        ),*/
                        array(
                            'name' => 'filiation_1',
                            'htmlOptions' => array('width' => '400px')
                        ),
                        array(
                            'name' => 'birthday',
                            'filter' => false
                        ),),
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="columntwo">
    </div>
</div>

</div>


<script>

    window.onload = function () {
        $("input, textarea, select").attr('disabled', true);
        $("#tab-student-identify, #tab-student-address, #tab-student-data, #tab-student-enrollment").click(function(){
            $("input, textarea, select").attr('disabled', true);
        });
        $(".buttons").remove()
    }

</script>

