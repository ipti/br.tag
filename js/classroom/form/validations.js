  
////////////////////////////////////////////////
// Validations                                //
////////////////////////////////////////////////
$(form+'name').focusout(function() {
    var id = '#'+$(this).attr("id");

    $(id).val($(id).val().toUpperCase());

    if(!validateClassroomName($(id).val())){ 
        $(id).attr('value','');
        addError(id, "Campo Nome não está dentro das regras.");
    }else{
        removeError(id);
    }
});
$(form+'school_year').focusout(function() {
    var id = '#'+$(this).attr("id");

    $(id).val($(id).val().toUpperCase());

    if(!validateYear($(id).val())){ 
        $(id).attr('value','');
        addError(id, "Campo não está dentro das regras.");
    }else{
        removeError(id);
    }
});
$(form+'initial_time').mask("99:99");
$(form+'initial_time').focusout(function() { 
    var id = '#'+$(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    var hour = form+'initial_hour';
    var minute = form+'initial_minute';

    if(!validateTime($(id).val())) {
        $(id).attr('value','');
        $(hour).attr('value','');
        $(minute).attr('value','');
        addError(id, "Campo Hora Inicial não está dentro das regras.");
    }
    else {
        var time = $(id).val().split(":");
        time[1] = Math.floor(time[1]/5) * 5;
        $(hour).attr('value',time[0]=='0'?'00':time[0]);
        $(minute).attr('value',time[1]=='0'?'00':time[1]);
        removeError(id);
    }
});
$(form+'final_time').mask("99:99");
$(form+'final_time').focusout(function() { 
    var id = '#'+$(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    var hour = form+'final_hour';
    var minute = form+'final_minute';

    if(!validateTime($(id).val()) || $(form+'final_time').val() <= $(form+'initial_time').val()) {
        $(id).attr('value','');
        $(hour).attr('value','');
        $(minute).attr('value','');
        addError(id, "Campo Hora Final não está dentro das regras.");
    }
    else {
        var time = $(id).val().split(":"); 
        time[1] = Math.floor(time[1]/5) * 5;
        $(hour).attr('value',time[0]=='0'?'00':time[0]);
        $(minute).attr('value',time[1]=='0'?'00':time[1]);
        removeError(id);
    }
});
$(form+'week_days input[type=checkbox]').change(function(){
    var id = '#'+$(form+'week_days').attr("id");
    if($(form+'week_days input[type=checkbox]:checked').length == 0){
        addError(id, "Campo não está dentro das regras.");
    }else{
        removeError(id);
    }
});
$(form+'week_days').focusout(function(){
    var id = '#'+$(this).attr("id");
    if($(form+'week_days input[type=checkbox]:checked').length == 0){
        addError(id, "Campo não está dentro das regras.");
    }else{
        removeError(id);
    }
});   
//Validação da disciplina
$("#discipline").change(function(){
    var id = '#discipline';
    if($(id).val().length == 0){
        addError(id, "Selecione a Disciplina."); 
    }else{
        removeError(id);
    }
});
//Validação da Classroom
$(formClassBoard+'classroom_fk').change(function(){
    var id = formClassBoard+'classroom_fk';
    calendar.fullCalendar('removeEvents');
    if($(id).val().length == 0){
        addError(id, "Selecione a Turma."); 
    }else{
        removeError(id);
    }
});