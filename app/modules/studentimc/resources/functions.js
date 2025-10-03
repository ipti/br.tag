$('.js-height, .js-weight').on('input', function() {
    var height = parseFloat($('.js-height').val());
    var weight = parseFloat($('.js-weight').val());
    var imc = weight / (height * height);
    $('.js-imc').val(isNaN(imc) ? '' : imc.toFixed(2));
});


$('.js-height, .js-weight').on('input', function() {
    $(this).val($(this).val().replace(/[^0-9.]/g, ''))
});
