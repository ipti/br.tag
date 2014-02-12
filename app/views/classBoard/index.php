<?php
/* @var $this ClassBoardController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Class Boards',
);

$this->menu=array(
	array('label'=>'Create ClassBoard', 'url'=>array('create')),
	array('label'=>'Manage ClassBoard', 'url'=>array('admin')),
);
?>



<h1>Class Boards</h1>

<div class="innerLR home">
    <div class="row-fluid">
<div id='loading' style='display:none'>loading...</div>
<div id='calendar'></div>
    </div></div>
<script type='text/javascript'>
	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		var calendar = $('#calendar').fullCalendar({
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

			header: { left: 'title', center: '', right: '', },

			titleFormat: { week: "MMMM", },

                        <?php //@done s2 - Traduzir dias da semana e meses do fullCalendar?>
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setenbro','Outubro','Novembro','Dezembro'],
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],

			selectable: true,
			selectHelper: true,                        
                        
                        <?php //@todo s2 - Criar o evento que importa os dados do banco?>
			//events: "json-events.php",
                        //////////////////////////////Remover isso, serve só para teste visual
			events: [
				{
					id: 1,
					title: 'Repeating Event',
					start: new Date(y, m, d, 5, 0),
				},
				{
					id: 2,
					title: 'Click for Google',
					start: new Date(y, m, d, 4, 0),
					//url: 'http://google.com/'
				}
			],
                        //////////////////////////////
                
                
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
