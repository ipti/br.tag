////////////////////////////////////////////////
// Validations                                //
////////////////////////////////////////////////
$(form + 'name').focusout(function () {
    var id = '#' + $(this).attr("id");

    $(id).val($(id).val().toUpperCase());

    if (!validateClassroomName($(id).val())) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 4.");
    } else {
        removeError(id);
    }
});
$(form + 'initial_time').mask("99:99");
$(form + 'initial_time').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    var hour = form + 'initial_hour';
    var minute = form + 'initial_minute';

    if (!validateTime($(id).val())) {
        $(id).attr('value', '');
        $(hour).attr('value', '');
        $(minute).attr('value', '');
        addError(id, "O horário deve ser válido e inferior ao horário final.");
    }
    else {
        var time = $(id).val().split(":");
        time[1] = Math.floor(time[1] / 5) * 5;
        $(hour).attr('value', time[0] == '0' ? '00' : time[0]);
        $(minute).attr('value', time[1] == '0' ? '00' : time[1]);
        removeError(id);
    }
});
$(form + 'final_time').mask("99:99");
$(form + 'final_time').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    var hour = form + 'final_hour';
    var minute = form + 'final_minute';

    if (!validateTime($(id).val()) || $(form + 'final_time').val() <= $(form + 'initial_time').val()) {
        $(id).attr('value', '');
        $(hour).attr('value', '');
        $(minute).attr('value', '');
        addError(id, "O horário deve ser válido e superior ao horário inicial.");
    }
    else {
        var time = $(id).val().split(":");
        time[1] = Math.floor(time[1] / 5) * 5;
        $(hour).attr('value', time[0] == '0' ? '00' : time[0]);
        $(minute).attr('value', time[1] == '0' ? '00' : time[1]);
        removeError(id);
    }
});
$(form + 'week_days input[type=checkbox]').change(function () {
    var id = '#' + $(form + 'week_days').attr("id");
    if ($(form + 'week_days input[type=checkbox]:checked').length == 0) {
        addError(id, "Escolha ao menos um dia.");
    } else {
        removeError(id);
    }
});
$(form + 'week_days').focusout(function () {
    var id = '#' + $(this).attr("id");
    if ($(form + 'week_days input[type=checkbox]:checked').length == 0) {
        addError(id, "Escolha ao menos um dia.");
    } else {
        removeError(id);
    }
});
//Validação da disciplina
$("#discipline").change(function () {
    var id = '#discipline';
    if ($(id).val().length == 0) {
        addError(id, "Selecione a Disciplina.");
    } else {
        removeError(id);
    }
});
$(".save-classroom").click(function () {
    var error = false;
    var message = "";
    if ($("#Classroom_name").val() === "") {
        error = true;
        message += "Campo <b>Nome</b> é obrigatório.<br>";
    }
    if ($("#Classroom_modality").val() === "") {
        error = true;
        message += "Campo <b>Modalidade</b> é obrigatório.<br>";
    }
    if ($("#Classroom_edcenso_stage_vs_modality_fk").val() === "") {
        error = true;
        message += "Campo <b>Etapa de Ensino</b> é obrigatório.<br>";
    }
    if ($("#SchoolIdentification_edcenso_uf_fk").val() === "") {
        error = true;
        message += "Campo <b>Estado</b> é obrigatório.<br>";
    }
    if ($("#Classroom_initial_time").val() === "") {
        error = true;
        message += "Campo <b>Horário Inicial</b> é obrigatório.<br>";
    }
    if ($("#Classroom_final_time").val() === "") {
        error = true;
        message += "Campo <b>Horário Final</b> é obrigatório.<br>";
    }
    if ($("#Classroom_assistance_type").val() === "") {
        error = true;
        message += "Campo <b>Tipo de Atendimento</b> é obrigatório.<br>";
    }
    if (!$("#Classroom_week_days input[type=checkbox]:checked").length) {
        error = true;
        message += "Campo <b>Dias da semana</b> é obrigatório. Selecione ao menos um dia.";
    }
    if (error) {
        $(this).closest("form").find(".classroom-error").html(message).show();
    } else {
        $(this).closest("form").find(".classroom-error").hide();
        $('#teachingData').val(JSON.stringify(teachingData));
        $('#disciplines').val(JSON.stringify(disciplines));
        $(this).closest('form').submit();
    }
});