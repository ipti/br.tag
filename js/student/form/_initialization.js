jQuery(function($) {
    $(formIdentification + 'filiation').trigger('change');
    $(formIdentification + 'nationality').trigger('change');
    $(formIdentification + 'deficiency').trigger('change');
});

$(document).ready(function(){
    var simple = getUrlVars()['simple'];
    if (simple == '1') {
        $("#tab-student-documents").hide();
        $(".control-group").hide();
        $(".required").parent().show();
    }
    $(".tab-student").show();
    $(".tab-content").show();

    if ($(formIdentification + "deficiency_type_blindness").is(":checked")){
        $(formIdentification + "deficiency_type_low_vision").attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafness").attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafblindness").attr("disabled", "disabled");
    }
    if ($(formIdentification + "deficiency_type_low_vision").is(":checked")){
        $(formIdentification + "deficiency_type_blindness").attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafblindness").attr("disabled", "disabled");
    }
    if ($(formIdentification + "deficiency_type_deafness").is(":checked")){
        $(formIdentification + "deficiency_type_blindness").attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_disability_hearing").attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafblindness").attr("disabled", "disabled");
    }
    if ($(formIdentification + "deficiency_type_disability_hearing").is(":checked")){
        $(formIdentification + "deficiency_type_deafness").attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafblindness").attr("disabled", "disabled");
    }
    if ($(formIdentification + "deficiency_type_deafblindness").is(":checked")){
        $(formIdentification + "deficiency_type_blindness").attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_low_vision").attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafness").attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_disability_hearing").attr("disabled", "disabled");
    }
    if ($(formIdentification + "deficiency_type_intelectual_disability").is(":checked")){
        $(formIdentification + "deficiency_type_gifted").attr("disabled", "disabled");
    }
    if ($(formIdentification + "deficiency_type_gifted").is(":checked")){
        $(formIdentification + "deficiency_type_intelectual_disability").attr("disabled", "disabled");
    }


});

$('.heading-buttons').css('width', $('#content').width());

$(document).on("submit", "#student", function(){
    $(formIdentification + "responsable_telephone").unmask();
    $(formIdentification + "responsable_cpf").unmask();
    $(formIdentification + "filiation_1_cpf").unmask();
    $(formIdentification + "filiation_2_cpf").unmask();
    $(formDocumentsAndAddress + "cpf").unmask();
    $(formDocumentsAndAddress + "cep").unmask();
});

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}