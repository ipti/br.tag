(function ($) {
    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }

    function loadDisciplines(classroomId) {
        var disciplineSelect = $('#macete-diary-discipline');
        disciplineSelect.prop('disabled', true).html('<option value="">Todos os componentes</option>');

        if (!classroomId) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: '?r=macete/diary/getDisciplines',
            dataType: 'json',
            data: { classroom: classroomId },
        }).done(function (disciplines) {
            $.each(disciplines || [], function () {
                disciplineSelect.append(
                    $('<option>').val(this.id).text(this.name)
                );
            });
            disciplineSelect.prop('disabled', false);
        });
    }

    function renderSummary(data) {
        $('#macete-diary-total-scheduled').text(data.totalScheduled);
        $('#macete-diary-total-registered').text(data.totalRegistered);
        $('#macete-diary-summary').removeClass('hide');

        var body = $('#macete-diary-table-body');
        body.empty();

        if (!data.days.length) {
            body.append('<tr><td colspan="5">Nenhuma aula prevista no quadro de horário para o período selecionado.</td></tr>');
        }

        $.each(data.days, function () {
            var day = this;
            var situacao = day.registered
                ? '<span class="t-badge-success">Registrada</span>'
                : '<span class="t-badge-warning">Pendente</span>';

            if (!day.records.length) {
                var row = $('<tr>').append(
                    $('<td>').text(day.day),
                    $('<td>').html(situacao),
                    $('<td>').text('—'),
                    $('<td>').text('—'),
                    $('<td>').text('—')
                );
                body.append(row);
                return;
            }

            $.each(day.records, function () {
                var record = this;
                var row = $('<tr>').append(
                    $('<td>').text(day.day),
                    $('<td>').html(situacao),
                    $('<td>').text(record.plan || '—'),
                    $('<td>').text(record.status || '—'),
                    $('<td>').html(escapeHtml(record.content || '').substring(0, 200))
                );
                body.append(row);
            });
        });

        $('#macete-diary-table').removeClass('hide');
    }

    function search() {
        var classroomId = $('#macete-diary-classroom').val();
        var disciplineId = $('#macete-diary-discipline').val();
        var month = $('#macete-diary-month').val();
        var year = $('#macete-diary-year').val();

        $('#macete-diary-error').addClass('hide').text('');

        if (!classroomId) {
            $('#macete-diary-error').removeClass('hide').text('Selecione uma turma.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: '?r=macete/diary/getSummary',
            dataType: 'json',
            data: { classroom: classroomId, discipline: disciplineId, month: month, year: year },
        }).done(function (data) {
            if (!data.valid) {
                $('#macete-diary-error').removeClass('hide').text(data.error || 'Não foi possível buscar o diário.');
                $('#macete-diary-summary, #macete-diary-table, #macete-diary-print').addClass('hide');
                return;
            }

            renderSummary(data);

            var printUrl = '?r=schoolreport/reports/MaceteDiaryReport'
                + '&classroomId=' + encodeURIComponent(classroomId)
                + '&month=' + encodeURIComponent(month)
                + '&year=' + encodeURIComponent(year)
                + '&disciplineId=' + encodeURIComponent(disciplineId || 'null');
            $('#macete-diary-print').attr('href', printUrl).removeClass('hide');
        });
    }

    $(document).ready(function () {
        $('#macete-diary-classroom').on('change', function () {
            loadDisciplines($(this).val());
        });
        $('#macete-diary-search').on('click', search);
    });
})(jQuery);
