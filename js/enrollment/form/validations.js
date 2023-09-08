/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('#StudentEnrollment_school_admission_date').mask("00/00/0000", {placeholder: "dd/mm/aaaa"});
$('#StudentEnrollment_school_admission_date').focusout(function () {
    var id = '#' + $(this).attr("id");
    var school_admission_date = stringToDate($('#StudentEnrollment_school_admission_date').val());


    if ((!validateDate($('#StudentEnrollment_school_admission_date').val()) || !validateYear(school_admission_date.year)) && ($(id).val() != '')) {
        //$(formIdentification + 'birthday').attr('value', '');
        addError(id, "Informe uma data válida no formato Dia/Mês/Ano. Não pode ser superior a data atual.");
    } else {
        removeError(id);
    }
});

function checkMulticlass() {

    var multi = $(cls + " option:selected").attr('id');
    if (multi == 1) {
        $('#multiclass').show();
    } else {
        $('#multiclass').hide();
    }

    //console.log(multi);
    //console.log($(cls + " option:selected").attr('value'));
}

$(".save-enrollment").click(function () {
    var error = false;
    var message = "";
    if ($("#StudentEnrollment_school_admission_date").val() === "") {
        error = true;
        message += "Preencha o campo Data de Ingresso na Escola";
    }
    if ($("#StudentEnrollment_public_transport").is(":checked") && $("#StudentEnrollment_transport_responsable_government").val() === "") {
        error = true;
        message += "Quando o campo <b>Transporte escolar público</b> é marcado, o campo <b>Poder público responsável pelo transporte escolar</b> é obrigatório.<br>";
    }
    if ($("#StudentEnrollment_public_transport").is(":checked") && !$("#transport_type input[type=checkbox]:checked").length) {
        error = true;
        message += "Quando o campo <b>Transporte escolar público</b> é marcado, o campo <b>Tipo de Transporte</b> é obrigatório. Selecione ao menos uma opção<br>";
    }
    if ($("#StudentEnrollment_public_transport").is(":checked")
        && ($("#StudentEnrollment_vehicle_type_van").is(":checked") && $("#StudentEnrollment_vehicle_type_microbus").is(":checked") && $("#StudentEnrollment_vehicle_type_bus").is(":checked")
            && $("#StudentEnrollment_vehicle_type_bike").is(":checked") && $("#StudentEnrollment_vehicle_type_animal_vehicle").is(":checked") && $("#StudentEnrollment_vehicle_type_other_vehicle").is(":checked"))) {
        error = true;
        message += "Não pode marcar todos os seguintes campos: <b>Van / Kombis</b>, <b>Microônibus</b>, <b>Ônibus</b>, <b>Bicicleta</b>, <b>Tração animal</b> e <b>Rodoviário - Outro</b>. Desmarque algumas dessas opções.<br>";
    }
    if ($("#StudentEnrollment_public_transport").is(":checked")
        && ($("#StudentEnrollment_vehicle_type_waterway_boat_5").is(":checked") && $("#StudentEnrollment_vehicle_type_waterway_boat_5_15").is(":checked")
            && $("#StudentEnrollment_vehicle_type_waterway_boat_15_35").is(":checked") && $("#StudentEnrollment_vehicle_type_waterway_boat_35").is(":checked"))) {
        error = true;
        message += "Não pode marcar todos os seguintes campos: <b>Embarcação - Capacidade de até 5 alunos</b>, <b>Embarcação - Entre 5 a 15 alunos</b>, <b>Embarcação - Entre 15 a 35 alunos</b> e <b>Embarcação - Acima de 35 alunos</b>. Desmarque algumas dessas opções.<br>";
    }
    if (error) {
        $("html, body").animate({scrollTop: 0}, "fast");
        $(this).closest("form").find(".enrollment-error").addClass("alert-error").removeClass("alert-warning").html(message).show();
    } else {
        if ($("#StudentEnrollment_classroom_fk") !== "" && $("#StudentEnrollment_classroom_fk option:selected").closest("optgroup").attr("label") !== $("#SchoolIdentification_inep_id option:selected").text()) {
            $(this).closest("form").find(".enrollment-error").addClass("alert-warning").removeClass("alert-error").html("Você está atualizando a matrícula de um aluno que está em uma turma diferente da escola selecionada, <b>" + $("#SchoolIdentification_inep_id option:selected").text() + "</b>. É isso mesmo que você deseja? <span class='yes-update'>SIM</span> ou <span class='no-update'>NÃO</span>.").show();
        } else {
            $(this).closest("form").find(".enrollment-error").hide();
            $("#student input").removeAttr("disabled");
            $("#student select").removeAttr("disabled").trigger("change.select2");
            $(this).closest("form").submit();
        }
    }
});

$(document).on("click", ".yes-update", function() {
    $("#student input").removeAttr("disabled");
    $("#student select").removeAttr("disabled").trigger("change.select2");
    $(this).closest("form").submit();
});

$(document).on("click", ".no-update", function() {
    $(this).closest("form").find(".enrollment-error").hide();
});