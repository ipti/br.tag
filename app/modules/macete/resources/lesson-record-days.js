(function ($) {
    function resetMonths() {
        var monthSelect = $('#macete-record-month');
        monthSelect.prop('disabled', true).html('<option value="">Selecione a turma primeiro</option>');
        $('#macete-record-days').empty();
        $('.js-macete-record-subtitle').addClass('hide');
        $('#macete-record-print').addClass('hide');
    }

    function loadMonths(classroomId) {
        resetMonths();
        $('#macete-record-error').addClass('hide');

        if (!classroomId) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: '?r=macete/lessonsrecord/getMonths',
            dataType: 'json',
            data: { classroom: classroomId },
        }).done(function (data) {
            if (!data.valid) {
                $('#macete-record-error').removeClass('hide').text(data.error || 'Não foi possível carregar os meses.');
                return;
            }

            var monthSelect = $('#macete-record-month');
            monthSelect.html('<option value="">Selecione o mês</option>');
            $.each(data.months, function () {
                monthSelect.append(
                    $('<option>').val(this.month + '-' + this.year).text(this.label)
                );
            });
            monthSelect.prop('disabled', false);
        });
    }

    function loadDays(classroomId, month, year) {
        $('#macete-record-days').empty();
        $('.js-macete-record-subtitle').addClass('hide');
        $('#macete-record-error').addClass('hide');
        $('#macete-record-print').addClass('hide');

        $.ajax({
            type: 'POST',
            url: '?r=macete/lessonsrecord/getDays',
            dataType: 'json',
            data: { classroom: classroomId, month: month, year: year },
        }).done(function (data) {
            if (!data.valid) {
                $('#macete-record-error').removeClass('hide').text(data.error || 'Não há dias letivos nesse mês.');
                return;
            }

            var printUrl = '?r=schoolreport/reports/MaceteDiaryReport'
                + '&classroomId=' + encodeURIComponent(data.classroom_fk)
                + '&month=' + encodeURIComponent(month)
                + '&year=' + encodeURIComponent(year)
                + '&disciplineId=null';
            $('#macete-record-print').attr('href', printUrl).removeClass('hide');

            var container = $('#macete-record-days');
            $.each(data.days, function () {
                var day = this;
                var badgeClass = day.registered ? 't-tag-success' : 't-tag-warning';
                var badgeText = day.registered ? 'Registrada' : 'Pendente';
                var dateParts = day.date.split('-');
                var formattedDate = dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0];
                var url = '?r=macete/lessonsrecord/create'
                    + '&classroomId=' + encodeURIComponent(data.classroom_fk)
                    + '&date=' + encodeURIComponent(day.date);

                if (day.registered && day.records.length === 1) {
                    url = '?r=macete/lessonsrecord/update&id=' + encodeURIComponent(day.records[0].id);
                }

                var card = $('<div class="column clearfix no-grow">').append(
                    $('<a class="t-cards">').attr('href', url).append(
                        $('<div class="t-cards-content">').append(
                            $('<div>').css({ display: 'flex', 'align-items': 'center' }).append(
                                $('<div>').addClass(badgeClass).text(badgeText),
                                $('<div class="t-tag-primary">').text(day.weekDay)
                            ),
                            $('<div class="t-cards-title">').text(formattedDate)
                        )
                    )
                );
                container.append(card);
            });

            $('.js-macete-record-subtitle').removeClass('hide');
        });
    }

    $('#macete-record-classroom').on('change', function () {
        loadMonths($(this).val());
    });

    $('#macete-record-month').on('change', function () {
        var classroomId = $('#macete-record-classroom').val();
        var value = $(this).val();
        if (!classroomId || !value) {
            return;
        }
        var parts = value.split('-');
        loadDays(classroomId, parts[0], parts[1]);
    });
})(jQuery);
