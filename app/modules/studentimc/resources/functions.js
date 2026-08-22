$('.js-height, .js-weight').on('input', function () {
    let heightMeters = parseFloat($('.js-height').val()) / 100;
    let weight = parseFloat($('.js-weight').val());
    let imc = weight / (heightMeters * heightMeters);
    $('.js-imc').val(isNaN(imc) ? '' : imc.toFixed(2));
});

$('.js-height').on('input', function () {
    $(this).val($(this).val().replace(/[^0-9]/g, ''))
});

$('.js-weight').on('input', function () {
    $(this).val($(this).val().replace(/[^0-9.]/g, ''))
});

function showReportbutton(classroom) {

    let button = `<a class="t-button-secondary" href="?r=forms/studentIMCReport&classroomId=${classroom}">
                    <span class="t-icon-printer"></span>
                    Relatório de Acompanhamento de Saúde da Turma
                </a>`
    $('.js-report-button').html(button);
    $('.js-report-button').show();
}

$(document).on('change', '.js-food-allergies-toggle', function () {
    $('.js-food-allergies-detail').toggleClass('hide', !$(this).is(':checked'));
});

$(document).on('change', '#food_allergy_type_OUTRAS', function () {
    $('.js-food-allergy-other').toggleClass('hide', !$(this).is(':checked'));
});

$('.js-classroom').on('change', function () {
    let classroom = $(this).val();

    showReportbutton(classroom);

    $.ajax({
        url: '?r=studentimc/studentimc/renderStudentTable',
        method: 'GET',
        data: { classroomId: classroom },
        success: function (response) {

            $('.js-studentTable').html(DOMPurify.sanitize(response));
            initDatatable();
        },
        error: function () {
            alert('Erro ao carregar a tabela de alunos.');
        }
    });
});
