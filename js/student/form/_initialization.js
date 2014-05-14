jQuery(function($) {
    $(formIdentification + 'filiation').trigger('change');
    $(formIdentification + 'nationality').trigger('change');
    $(formIdentification + 'deficiency').trigger('change');
});

$('.heading-buttons').css('width', $('#content').width());