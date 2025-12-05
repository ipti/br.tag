$('.js-requires_exam').on('change', function() {
    console.log('changed');
    if ($(this).is(':checked')) {
        $('.js-report_text').hide();
    } else {
        $('.js-report_text').show();
    }
});
$('.js-requires_exam').trigger('change');
