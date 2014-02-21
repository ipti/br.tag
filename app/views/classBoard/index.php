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
                                                if(events != null){
                                                    $.each(events, function(i, event){
                                                        calendar.fullCalendar('renderEvent',event);
                                                    });     
                                                }
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

    <div id="create-dialog-form" title="<?php echo Yii::t('default', 'Insert class'); ?>">
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label( Yii::t('default','Discipline'), 'discipline', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::dropDownList('discipline', '', CHtml::listData(EdcensoDiscipline::model()->findAll(array('order' => 'name')), 'id', 'name'),array('prompt'=> 'Selecione a disciplina','class' => 'select-search-on')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="update-dialog-form" title="<?php echo Yii::t('default', 'Update class'); ?>">
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label( Yii::t('default','Discipline'), 'update-discipline', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::dropDownList('update-discipline', '', CHtml::listData(EdcensoDiscipline::model()->findAll(array('order' => 'name')), 'id', 'name'),array('prompt'=> 'Selecione a disciplina','class' => 'select-search-on')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script type='text/javascript'>

    var form = "#ClassBoard_";
    <?php //@done s2 - Criar modal ao clicar na tabela ?>
    <?php //@done s2 - Corrigir problemas do submit automático ?>
    <?php //@done s2 - Corrigir problemas do Layout ?>
    var lesson = {};
    var oldLesson = {};
    var lesson_id = 1;
    var lesson_start = 1;
    var lesson_end = 2;
    
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var calendar;
    
    var myCreateDialog;
    var myUpdateDialog;
    
    var discipline = $("#discipline");
    var uDiscipline = $("#update-discipline");
    
    
    //@done S2 - Reduzir caracteres do evento
    //@done S2 - Comportar o horário na tabela de classboard
    //Cria estrutura de uma aula
    //Retorna um array
    //O Ajax da problema de recursividade se colocado aqui
    createNewLesson = function() {
        lesson = {
            id: lesson_id++,
            id_db: 0,
            title: (discipline.find('option:selected').text().length > 40) ? discipline.find('option:selected').text().substring(0,37) + "..." : discipline.find('option:selected').text(),
            discipline: discipline.val(),
            start: lesson_start,
            end: lesson_end,
            classroom: $(form+'classroom_fk').val(),
        };
        return lesson;
    }
    
    //Atualiza estrutura de uma aula
    //Retorna um array
    //O Ajax da problema de recursividade se colocado aqui
    updateLesson = function(l) {
        lesson = {
            id: l.id,
            db: l.db,
            title: l.title,
            discipline: uDiscipline.val(),
            start: l.start,
            end: l.end,
            classroom: l.classroom,
        };
        return lesson;
    }
    
    
    //Ao clicar ENTER no formulário adicionar aula
    $('#dialog-form').keypress(function(e) {
        if (e.keyCode == $.ui.keyCode.ENTER) {
            e.preventDefault();
        }
    });
   
   <?php //@done s2 - Validação da disciplina?>
    //Validação da disciplina
    $("#discipline").change(function(){
        var id = '#discipline';
        if($(id).val().length == 0){
            addError(id, "Selecione a Disciplina."); 
        }else{
            removeError(id);
        }
    });
    
   <?php //@done s2 - Validação da classroom?>
    //Validação da Classroom
    $(form+'classroom_fk').change(function(){
        var id = form+'classroom_fk';
        calendar.fullCalendar('removeEvents');
        if($(id).val().length == 0){
            addError(id, "Selecione a Turma."); 
        }else{
            removeError(id);
        }
    });
    
    
    
    $(document).ready(function() {
    
        //Cria o Dialogo de CRIAÇÃO
        myCreateDialog = $("#create-dialog-form").dialog({
            autoOpen: false,
            height: 215,
            width: 230,
            modal: true,
            draggable: false,
            resizable: false,
            buttons: {
                "<?php echo Yii::t('default','Create'); ?>": function(){
                    if(discipline.val().length != 0){
                        var l = createNewLesson();                    
                        <?php //@done s2 - Ajax da criação de lessons ?>
                        $.ajax({
                            type:'POST',
                            url:'<?php echo CController::createUrl('classBoard/addLesson'); ?>',
                            success:function(e){
                                var event = jQuery.parseJSON(e);
                                calendar.fullCalendar('renderEvent',event,true);
                                myCreateDialog.dialog("close");
                                $('body').css('overflow','scroll');
                            },
                            data:{'lesson': l }
                        });
                    }else{
                        var id = '#discipline';
                        addError(id, "Selecione a Disciplina");              
                    }
                },
                <?php echo Yii::t('default','Cancel'); ?>: function() {
                    $(this).dialog("close");
                    $('body').css('overflow','scroll');
                }
            },
        });


        //Cria o Dialogo de ALTERAÇÃO e REMOÇÃO
        myUpdateDialog = $("#update-dialog-form").dialog({
            autoOpen: false,
            height: 215,
            width: 250,
            modal: true,
            draggable: false,
            resizable: false,
            create: function( event, ui ) {
                uDiscipline.val(lesson.discipline).trigger('change');
            },
            buttons: {
                "<?php echo Yii::t('default','Update'); ?>": function(){
                    if(uDiscipline.val().length != 0){
                        lesson.discipline = uDiscipline.val();
                        var l = lesson;  
                        <?php //@done s2 - Ajax da criação de lessons ?>
                        $.ajax({
                            type:'POST',
                            url:'<?php echo CController::createUrl('classBoard/updateLesson'); ?>',
                            success:function(e){
                                var event = jQuery.parseJSON(e);
                                calendar.fullCalendar('removeEvents',event.id);
                                calendar.fullCalendar('renderEvent',event,true);
                                myUpdateDialog.dialog("close");
                                $('body').css('overflow','scroll');
                            },
                            data:{'lesson': l }
                        });
                    }else{
                        var id = '#update-discipline';
                        addError(id, "Selecione a Disciplina");              
                    }
                },
                <?php echo Yii::t('default','Delete'); ?>: function() {
                        lesson.discipline = uDiscipline.val();
                        var l = lesson;  
                        <?php //@done s2 - Ajax da criação de lessons ?>
                        $.ajax({
                            type:'POST',
                            url:'<?php echo CController::createUrl('classBoard/deleteLesson'); ?>',
                            success:function(){
                                calendar.fullCalendar('removeEvents',l.id);
                                myUpdateDialog.dialog("close");
                                $('body').css('overflow','scroll');
                            },
                            data:{'lesson': l }
                        });
                },
                <?php echo Yii::t('default','Cancel'); ?>: function() {
                    myUpdateDialog.dialog("close");
                    $('body').css('overflow','scroll');
                }
            },
        });



        
        //Cria o calendário semanal de aulas
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
            slotEventOverlap: true,
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
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            selectable: true,
            selectHelper: true,

            <?php //@done s2 - Criar o evento que importa os dados do banco        ?>
            <?php //@done s2 - Atualizar para a nova estrutura do bando o evento que importa os dados do banco        ?>
            events: '', //'<?php echo CController::createUrl('classBoard/getClassBoard'); ?>',

            //Evento ao selecionar nos blocos de horários
            //Criar uma nova aula
            <?php //@done s2 - Criar tela de dialogo para CRIAR da aula        ?>
            select: function(start, end, allDay) {
                var id = form+'classroom_fk';
                if($(id).val().length != 0){
                    lesson_start = start;
                    lesson_end = end;
                    $("#create-dialog-form").dialog("open");
                    calendar.fullCalendar('unselect');
                    $('body').css('overflow','hidden');
                }else{
                    addError(id, "Selecione a Turma");
                } 
            },
            
            
            //Evento ao clicar nos blocos de horários existentes
            //Atualizar e Remover bloco
            eventClick: function(event){
                lesson = updateLesson(event);
                <?php //@done s2 - Criar tela de dialogo com opções de ALTERAR e REMOVER aula        ?>
                <?php //@done s2 - Criar função de REMOVER aula        ?>
                <?php //@done s2 - Criar função de ATUALIZAR aula        ?>
                var id = form+'classroom_fk';
                if($(id).val().length != 0){
                    uDiscipline.val(event.discipline).trigger('change');
                    $("#update-dialog-form").dialog("open");
                    calendar.fullCalendar('unselect');
                    $('body').css('overflow','hidden');
                }else{
                    addError(id, "Selecione a Turma");
                } 
            },
                    
                    
            //Evento ao mover um bloco de horário
            //Atualizar o bloco
            <?php //@done s2 - criar o evento que ATUALIZAR os dados do banco ao mover a aula        
                  //Esta adicionando o novo, mas não esta excluindo o velho
                  //@done s2 - Draggear evento grande apos renderizar e voltar pequeno nao funciona
                  //@todo s2 - Verificar choque de horários?>
            eventDrop: function(event, dayDelta, minuteDelta) {
                lesson = updateLesson(event);
                lesson.discipline = event.discipline;
                var l = lesson;  
                $.ajax({
                    type:'POST',
                    url:'<?php echo CController::createUrl('classBoard/updateLesson'); ?>',
                    success:function(e){
                        var event = jQuery.parseJSON(e);
                        calendar.fullCalendar('removeEvents',event.id);
                        calendar.fullCalendar('renderEvent',event,true);
                        myUpdateDialog.dialog("close");
                    },
                    data:{'lesson': l , 'days': dayDelta, 'minutes': minuteDelta}
                });
                
            },
                    
                    
            //Evento de carregamento do calendário
            loading: function(bool) {
                if (bool) $('#loading').show();
                else $('#loading').hide();
            }

        });
    });

    $('.heading-buttons').css('width', $('#content').width());
</script>
