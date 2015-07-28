jQuery(function($) {
    $(formIdentification + 'filiation').trigger('change');
    $(formIdentification + 'nationality').trigger('change');
    $(formIdentification + 'deficiency').trigger('change');
});

$(document).ready(function(){
    var simple = getUrlVars()['simple'];
    if (simple == '1'){
        $("#tab-student-documents").hide();
        $(".control-group").hide();
        $(".required").parent().show();
        $(".tab-student").show();
        $(".tab-content").show();
    } else {
        $(".tab-student").show();
        $(".tab-content").show();
    }
});

$('.heading-buttons').css('width', $('#content').width());

$(document).on("submit", "#student", function(){
    $(formIdentification + "responsable_telephone").unmask();
    $(formIdentification + "responsable_cpf").unmask();
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