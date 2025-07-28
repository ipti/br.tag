

$('.js-withoutCertificates').on('change', function () {
    const isChecked = $(this).is(':checked');
    const value = isChecked ? 'true' : 'false';

    const baseUrl = $('.js-export-link').attr('href').split('?')[0]; // remove parâmetros antigos

    const newUrl = baseUrl + '?withoutCertificates=' + value;

    $('.js-export-link').attr('href', newUrl);
})
