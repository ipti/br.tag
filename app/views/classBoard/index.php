<?php
/* @var $this ClassBoardController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('default', 'Class Boards'),
);

$this->menu=array(
	array('label'=>'Create ClassBoard', 'url'=>array('create')),
	array('label'=>'Manage ClassBoard', 'url'=>array('admin')),
);

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'classroom-form',
	'enableAjaxValidation'=>false,
)); 
?>

<?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
    <div class="span12">
        <div class="heading-buttons" data-spy="affix" data-offset-top="95" data-offset-bottom="0" class="affix">
            <div class="row-fluid">
                <div class="span8">
                    <h3><?php echo Yii::t('default', 'Class Boards'); ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span></h3>        
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
                            <div class="separator"></div>
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

                        <div class="span6">
                            <div class="separator"></div>
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
                        </div>
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


<script type='text/javascript'>
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var calendar;
        
	$(document).ready(function() {
		calendar = $('#calendar').fullCalendar({
                        <?php //@done s2 - Colocar data padrão?>
                        year: 1995, //Porque eu nasci em 1993.
                        month: 0,
                        date: 1,
                        theme: true,
                        
			defaultView: 'agendaWeek',

			allDaySlot: false,
			allDayDefault: false,

			slotEventOverlap: false,
			disableResizing: true,
			editable: true,

                        <?php //@done s2 - Limitar quantidade de slots que aparecem no Quadro de Horário?>
			firstHour: 1,
			minTime: 1,
			maxTime: 11,

			slotMinutes: 60,
			defaultEventMinutes: 60,

			axisFormat: "H 'Horário'",
			timeFormat: { agenda: 'h{ - h}' },

			columnFormat: { week: 'dddd', },

			header: { left: '', center: 'title', right: '', },

			titleFormat: { week: "MMMM", },

                        <?php //@done s2 - Traduzir dias da semana e meses do fullCalendar?>
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setenbro','Outubro','Novembro','Dezembro'],
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],

			selectable: true,
			selectHelper: true,                        
                        
                        <?php //@done s2 - Criar o evento que importa os dados do banco?>             
                        events: '<?php echo CController::createUrl('classBoard/getClassBoard');?>',
                                
                        <?php //@todo s2 - Criar tela de dialogo para CRIAR da aula?>
			select: function(start, end, allDay) {
				var title = prompt('Event Title:');
				if (title) {
					calendar.fullCalendar('renderEvent',
						{
							title: title,
							start: start,
							end: end,
							allDay: allDay
						},
						true // make the event "stick"
					);
				}
				calendar.fullCalendar('unselect');
			},
                
			eventClick: function(event){
                                <?php //@todo s2 - Criar tela de dialogo com opções de ALTERAR e REMOVER aula?>
                                <?php //@todo s2 - Criar função de REMOVER aula?>
                                <?php //@todo s2 - Criar função de ATUALIZAR aula?>
				calendar.fullCalendar( 'removeEvents' , event.id );		
			},
                        
			
                        <?php //@todo s2 - criar o evento que ATUALIZAR os dados do banco ao mover a aula?>
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
