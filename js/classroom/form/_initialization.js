$(document).ready(function() {
    ////////////////////////////////////////////////
    // Ajax Initialization                        //
    ////////////////////////////////////////////////
    $.ajax({
        'type':'POST',
        'url':baseUrl+'/index.php?r=classroom/getassistancetype',
        'cache':false,
        'data':$(form+'school_inep_fk').parents("form").serialize(),
        'success':function(result){
            result   = jQuery.parseJSON(result);
            var html = result.html;
            var val  = result.val;
            $(form+"assistance_type").html(html); 
            $(form+"assistance_type").val(val).trigger('change');
        }});
    $(form+"complementary_activity_type_1").val($.parseJSON(jsonCompActv));
});   
   
   
////////////////////////////////////////////////
// Submit Form                                //
////////////////////////////////////////////////
$('#enviar_essa_bagaca').click(function() { 
    $('#teachingData').val(JSON.stringify(teachingData)); 
    $('#disciplines').val(JSON.stringify(disciplines));
    var myEvents = [];
    $.each(calendar.fullCalendar('clientEvents'), function(i,e){
        var event = {};
        event.classroom = e.classroom;
        event.discipline =  e.discipline;
        event.end =  e.end;
        event.id =  e.id;
        event.id_db =  e.id_db;
        event.instructor =  e.instructor;
        event.start =  e.start;
        event.title =  e.title;
        myEvents.push(event);
    });
    $('#events').val(JSON.stringify(myEvents));
    $('form').submit();
});


//Ao clicar ENTER no formulário adicionar aula
$('#create-dialog-form, #teachingdata-dialog-form, #update-dialog-form').keypress(function(e) {
    if (e.keyCode === $.ui.keyCode.ENTER) {
        e.preventDefault();
    }
});

$('.heading-buttons').css('width', $('#content').width());