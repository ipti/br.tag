$(document).ready(function() {

    ////////////////////////////////////////////////
    // Dialogs                                     //
    ////////////////////////////////////////////////
    //Cria o Dialogo de TeachingData
    myTeachingDataDialog = $("#teachingdata-dialog-form").dialog({
        autoOpen: false,
        height: 430,
        width: 250,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [{
                text: btnCreate,
                click: function(){   
                    addTeachingData();
                    $(this).dialog("close");
            }},
            {
                text: btnCancel,
                click: function() {
                    $(this).dialog("close");
            }}

        ],
    });

    //Cria o Dialogo de CRIAÇÃO
    myCreateDialog = $("#create-dialog-form").dialog({
        autoOpen: false,
        height: 300,
        width: 230,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [
            {text: btnCreate,
                click: function() {
                    if (discipline.val().length != 0) {
                        var l = createNewLesson();
                        if (l.classroom == '') {
                            calendar.fullCalendar('renderEvent', l, true);
                            myCreateDialog.dialog("close");
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: addLessonUrl,
                                success: function(e) {
                                    var event = $.parseJSON(e);
                                    calendar.fullCalendar('renderEvent', event, true);
                                    myCreateDialog.dialog("close");
                                },
                                data: {'lesson': l}
                            });
                        }
                    } else {
                        var id = '#discipline';
                        addError(id, "Selecione a Disciplina");
                    }
                }
            },
            {text: btnCancel, click: function() {
                    $(this).dialog("close");
                }}
        ],
    });

    //Cria o Dialogo de ALTERAÇÃO e REMOÇÃO
    myUpdateDialog = $("#update-dialog-form").dialog({
        autoOpen: false,
        height: 300,
        width: 250,
        modal: true,
        draggable: false,
        resizable: false,
        create: function( event, ui ) {
            uDiscipline.val(lesson.discipline).trigger('change');
        },
        buttons: [
            {text: btnUpdate,
                click: function() {
                    if (uDiscipline.val().length != 0) {
                        lesson.discipline = uDiscipline.val();
                        var l = lesson;
                        if (l.classroom == '') {
                            l.title = disciplinesLabels[l.discipline];
                            calendar.fullCalendar('removeEvents', l.id);
                            calendar.fullCalendar('renderEvent', l, true);
                            myUpdateDialog.dialog("close");
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: updateLessonUrl,
                                success: function(e) {
                                    var event = $.parseJSON(e);
                                    calendar.fullCalendar('removeEvents', event.id);
                                    calendar.fullCalendar('renderEvent', event, true);
                                    myUpdateDialog.dialog("close");
                                },
                                data: {'lesson': l}
                            });
                        }
                    } else {
                        var id = '#update-discipline';
                        addError(id, "Selecione a Disciplina");
                    }
                }},
            {text: btnDelete,
                click: function() {
                    lesson.discipline = uDiscipline.val();
                    var l = lesson;
                    if (l.classroom == '') {
                        calendar.fullCalendar('removeEvents', l.id);
                        myUpdateDialog.dialog("close");
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: deleteLessonUrl,
                            success: function() {
                                calendar.fullCalendar('removeEvents', l.id);
                                myUpdateDialog.dialog("close");
                            },
                            data: {'lesson': l}
                        });
                    }
                }},
            {text: btnCancel, click: function() { myUpdateDialog.dialog("close");}}
        ],
    });
});

//////////////////////////////////////////////////
// Dialog Controls                            //
////////////////////////////////////////////////
$("#newDiscipline").click(function(){
    $("#teachingdata-dialog-form select").val('').trigger('change');
    $("#teachingdata-dialog-form").dialog('open');
});    