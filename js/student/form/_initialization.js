jQuery(function($) {
    $(formIdentification + 'filiation').trigger('change');
    $(formIdentification + 'nationality').trigger('change');
    $(formIdentification + 'deficiency').trigger('change');
});

$('.heading-buttons').css('width', $('#content').width());

$(document).on("submit", "#student", function(){
    $(formIdentification + "responsable_telephone").unmask();
    $(formIdentification + "responsable_cpf").unmask();
    $(formDocumentsAndAddress + "cpf").unmask();
    $(formDocumentsAndAddress + "cep").unmask();
});