$('.js-height, .js-weight').on('input', function () {
    var height = parseFloat($('.js-height').val());
    var weight = parseFloat($('.js-weight').val());
    var imc = weight / (height * height);
    $('.js-imc').val(isNaN(imc) ? '' : imc.toFixed(2));
});


$('.js-height, .js-weight').on('input', function () {
    $(this).val($(this).val().replace(/[^0-9.]/g, ''))
});

function showReportbutton(classroom) {

    var button =`<a class="t-button-secondary" href="?r=forms/studentIMCReport&classroomId=${classroom}">
                    <span class="t-icon-printer"></span>
                    Relatório de Acompanhamento de Saúde da Turma
                </a>`
    $('.js-report-button').html(button);
    $('.js-report-button').show();
}

$('.js-classroom').on('change', function () {
    var classroom = $(this).val();

    showReportbutton(classroom);

    $.ajax({
        url: '?r=studentimc/studentimc/renderStudentTable',
        method: 'GET',
        data: { classroomId: classroom },
        success: function (response) {

            $('.js-studentTable').html(response);
            initDatatable();
        },
        error: function () {
            alert('Erro ao carregar a tabela de alunos.');
        }
    });
});
