$(document).ready(function() {
    ////////////////////////////////////////////////
    // Calendar                                   //
    ////////////////////////////////////////////////
    //Cria o calendário semanal de aulas

    $('#tab-classboard, #button-next').click(function(){
        if(firstTime){
            firstTime = false;
            calendar = $('#calendar').fullCalendar({
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

                firstHour: 1,
                minTime: 1,
                maxTime: 11,
                slotMinutes: 60,
                defaultEventMinutes: 60,
                axisFormat: "H'º' 'Horário'",
                timeFormat: { agenda: "" },
                columnFormat: { week: 'dddd', },

                header: { left: '', center: '', right: '', },
                titleFormat: { week: "MMMM", },

                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                selectable: true,
                selectHelper: true,

                events: eventsUrl,

                //Evento ao selecionar nos blocos de horários
                //Criar uma nova aula
                select: function(start, end, allDay) {
                    var id = formClassBoard+'classroom_fk';
                    lesson_start = start;
                    lesson_end = end;

                    atualizaListadeInstrutores();
                    $("#create-dialog-form").dialog("open");

                    $(lessons).each(function(i, val){ 
                        v1 = val.start.getTime();
                        v2 = val.end == null ? v1 : val.end.getTime();
                        l1 = lesson_start.getTime();
                        l2 = lesson_end == null ? l1 : lesson_end.getTime();

                        if ((l1 < v1 && l2 <= v1)
                           || (l1 > v2 && l2 > v2)){
                        }else{
                            myCreateDialog.dialog('close');
                            //Pode-se criar um dialog para avisar o que ocorreu, mas acho que ficaria muito spam.
                        }
                    });

                    calendar.fullCalendar('unselect');
                },


                //Evento ao clicar nos blocos de horários existentes
                //Atualizar e Remover bloco
                eventClick: function(event){
                    lesson = updateLesson(event);         
                    atualizaListadeInstrutores();
                    $("#update-dialog-form").dialog("open");
                    calendar.fullCalendar('unselect');
                },


                //Evento ao mover um bloco de horário
                //Atualizar o bloco
                eventDrop: function(event, dayDelta, minuteDelta) {
                    lesson = updateLesson(event);
                    lesson.discipline = event.discipline;
                    var l = lesson;  
                    if(l.classroom == ''){
                        calendar.fullCalendar('removeEvents',l.id);
                        calendar.fullCalendar('renderEvent',l,true);
                        myUpdateDialog.dialog("close");
                    }else{
                        $.ajax({
                            type:'POST',
                            url: updateLessonUrl,
                            success:function(e){
                                var event = $.parseJSON(e);
                                calendar.fullCalendar('removeEvents',event.id);
                                calendar.fullCalendar('renderEvent',event,true);
                                myUpdateDialog.dialog("close");
                            },
                            data:{'lesson': l , 'days': dayDelta, 'minutes': minuteDelta,classroom_fk:classroomId}
                        });
                    }

                },


                //Evento de carregamento do calendário
                loading: function(bool) {
                    if (bool) $('#loading').show();
                    else $('#loading').hide();
                }

            });
        }

    });

        //====================END CALENDAR ==========
});
