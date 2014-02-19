<?php
/* @var $this ClassBoardController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    Yii::t('default', 'Class Boards'),
);

$this->menu = array(
    array('label' => 'Create ClassBoard', 'url' => array('create')),
    array('label' => 'Manage ClassBoard', 'url' => array('admin')),
);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classroom-form',
    'enableAjaxValidation' => false,
        ));
?>

<?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
    <div class="span12">
        <div class="heading-buttons" data-spy="affix" data-offset-top="95" data-offset-bottom="0" class="affix">
            <div class="row-fluid">
                <div class="span8">
                    <h3><?php echo Yii::t('default', 'Class Boards'); ?><span> | <?php echo Yii::t('help', 'ClassBoard Subtitle') ?></span></h3>        
                </div>
                <div class="buttons pull-right">
                    <button class="btn btn-primary btn-icon glyphicons circle_plus" id="new-class"><i></i><?php echo Yii::t('default', 'Generate classes') ?></button>
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="innerLR">


    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head">
            <ul class="tab-classboard">
                <li id="tab-classboard" class="active" ><a class="glyphicons user" href="#classboard" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Class Boards') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <!-- Tab content -->
                <div class="tab-pane active" id="classboard">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'classroom_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($model, 'classroom_fk', CHtml::listData(Classroom::model()->findAll('school_inep_fk=' . Yii::app()->user->school, array('order' => 'name')), 'id', 'name'), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on',
                                        'prompt' => 'Selecione a Turma',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('classBoard/getClassBoard'),
                                            'success' => "function(events){
                                                var events = jQuery.parseJSON(events);
                                                calendar.fullCalendar( 'removeEvents');
                                                $.each(events, function(i, event){
                                                    calendar.fullCalendar('renderEvent',event);
                                                });     
                                                }",
                                    )));
                                    ?>
                                    <?php echo $form->error($model, 'classroom_fk'); ?>
                                </div>
                            </div>
                        </div>

<!--                        <div class="span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'estimated_classes', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
//                                    foreach ([] as $discipline) {
//                                        echo '';
//                                    }
//                                    echo $form->dropDownList($model, 'classroom_fk', CHtml::listData(Classroom::model()->findAll('school_inep_fk=' . Yii::app()->user->school, array('order' => 'name')), 'id', 'name'), array(
//                                        'key' => 'id',
//                                        'class' => 'select-search-on',
//                                        'prompt' => 'Selecione a Turma',
//                                        'ajax' => array(
//                                            'type' => 'POST',
//                                            'url' => CController::createUrl('classBoard/getClassBoard'),
//                                            'success' => "function(data){}",
//                                    )));
                                    ?>
                                    <?php echo $form->error($model, 'classroom_fk'); ?>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="row-fluid">         
                        <div id='loading' style='display:none'>loading...</div>
                        <div id='calendar'></div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="dialog-form" title="<?php echo Yii::t('default', 'Insert class'); ?>">
    <p class="validateTips"></p>
    <form>
        <fieldset>
            <div class="control-group">
                <?php echo CHtml::label( Yii::t('default','Discipline'), 'discipline', array('class' => 'control-label')); ?>
                <div class="controls">
                    <?php echo CHtml::dropDownList('discipline', '', CHtml::listData(EdcensoDiscipline::model()->findAll(array('order' => 'name')), 'id', 'name'),array('prompt'=> 'Selecione a disciplina','class' => 'select-search-on')); ?>
                </div>
            </div>
        </fieldset>
    </form>
</div>



<script type='text/javascript'>

    var form = "#ClassBoard_";
    <?php //@done s2 - Criar modal ao clicar na tabela ?>
    <?php //@done s2 - Corrigir problemas do submit automático ?>
    <?php //@done s2 - Corrigir problemas do Layout ?>
    var lesson_id = 1;
    var lesson_start = 1;
    var lesson_end = 2;
    
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var calendar;
    
    var myDialog;
    
    var discipline = $("#discipline"),
    tips = $(".validateTips");
    
    $('#dialog-form').keypress(function(e) {
        if (e.keyCode == $.ui.keyCode.ENTER) {
            e.preventDefault();
            createNewLesson();
        }
    });
    
    createNewLesson = function() {
        lesson = {
            id: lesson_id++,
            id_db: 0,
            title: (discipline.find('option:selected').text().length > 40) ? discipline.find('option:selected').text().substring(0,37) + "..." : discipline.find('option:selected').text(),
            discipline_cod: discipline.val(),
            start: lesson_start,
            end: lesson_end
        };
        calendar.fullCalendar('renderEvent',lesson,true);
        myDialog.dialog("close");
    }
    
    $(document).ready(function() {
        myDialog = $("#dialog-form").dialog({
            autoOpen: false,
            height: 250,
            width: 350,
            modal: true,
            buttons: {
                "<?php echo Yii::t('default','Create'); ?>": createNewLesson,
                <?php echo Yii::t('default','Cancel'); ?>: function() {
                    $(this).dialog("close");
                }
            },
        });

        $("#new-class").click(function(event) {
            event.preventDefault();
            $("#dialog-form").dialog("open");
        });
        
        calendar = $('#calendar').fullCalendar({
            <?php //@done s2 - Colocar data padrão        ?>
            year: 1996, //Porque eu nasci em 1993.
            month: 0,
            date: 1,
            theme: true,
            firstDay:1,
            defaultView: 'agendaWeek',
            allDaySlot: false,
            allDayDefault: false,
            slotEventOverlap: false,
            disableResizing: true,
            editable: true,

            <?php //@done s2 - Limitar quantidade de slots que aparecem no Quadro de Horário        ?>
            firstHour: 1,
            minTime: 1,
            maxTime: 11,
            slotMinutes: 60,
            defaultEventMinutes: 60,
            axisFormat: "H'º' 'Horário'",
            timeFormat: { agenda: "" },
            columnFormat: { week: 'dddd', },

            <?php //@done s2 - Não é necessário colocar o mês (o quadro de aulas serve pro ano inteiro)         ?>
            header: { left: '', center: '', right: '', },
            titleFormat: { week: "MMMM", },

            <?php //@done s2 - Traduzir dias da semana e meses do fullCalendar        ?>
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setenbro', 'Outubro', 'Novembro', 'Dezembro'],
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            selectable: true,
            selectHelper: true,

            <?php //@done s2 - Criar o evento que importa os dados do banco        ?>
            events: '<?php echo CController::createUrl('classBoard/getClassBoard'); ?>',

            <?php //@done s2 - Criar tela de dialogo para CRIAR da aula        ?>
            select: function(start, end, allDay) {
                lesson_start = start;
                lesson_end = end;
                $("#dialog-form").dialog("open");
                calendar.fullCalendar('unselect');
            },

            eventClick: function(event){
                alert(event.id);
            <?php //@todo s2 - Criar tela de dialogo com opções de ALTERAR e REMOVER aula        ?>
            <?php //@todo s2 - Criar função de REMOVER aula        ?>
            <?php //@todo s2 - Criar função de ATUALIZAR aula        ?>
                calendar.fullCalendar('removeEvents', event.id);
            },

            <?php //@todo s2 - criar o evento que ATUALIZAR os dados do banco ao mover a aula        ?>
            eventDrop: function(event, delta) {
                alert(event.title + ' was moved ' + delta + ' days\n' +
                    '(should probably update your database)');
            },
            loading: function(bool) {
                if (bool) $('#loading').show();
                else $('#loading').hide();
            }

        });
    });

</script>
