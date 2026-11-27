(function ($) {
    $(function () {
        var labels = { grade: 'notas', frequency: 'frequência', lesson: 'aulas' };
        $('.diary-lock-notice').each(function () {
            var notice = this;
            var $notice = $(notice);
            var types = String($notice.attr('data-entry-lock-types')).split(',');
            var fixedClassroom = $notice.attr('data-entry-lock-classroom');
            var serial = 0;
            var frequencyLocked = false;
            var checkedClassroom = null;

            function refresh() {
                var classroom = fixedClassroom || $('#classroom').val();
                var requestId = ++serial;
                if (String(classroom || '') !== String(checkedClassroom || '')) frequencyLocked = false;
                checkedClassroom = classroom;
                notice.hidden = true;
                if (!classroom) return;
                $.ajax({
                    url: $notice.attr('data-entry-lock-url'),
                    data: { classroomId: classroom },
                    dataType: 'json',
                    cache: false
                }).done(function (status) {
                    if (requestId !== serial) return;
                    frequencyLocked = types.indexOf('frequency') !== -1 && status.frequency === true;
                    var blocked = types.filter(function (type) { return status[type] === true; });
                    if (!blocked.length) return;
                    $notice.find('.diary-lock-notice__title').text('Lançamento bloqueado');
                    $notice.find('.diary-lock-notice__message').text('Nesta turma, o lançamento de ' + blocked.map(function (type) { return labels[type]; }).join(', ') + ' está bloqueado. Para liberar, procure a administração.');
                    notice.hidden = false;
                }).fail(function () {
                    if (requestId !== serial) return;
                    $notice.find('.diary-lock-notice__title').text('Não foi possível consultar a liberação');
                    $notice.find('.diary-lock-notice__message').text('Atualize a página para verificar a situação desta turma.');
                    notice.hidden = false;
                });
            }

            if (!fixedClassroom) $('#classroom').on('change', refresh);
            if (types.indexOf('frequency') !== -1) {
                document.addEventListener('click', function (event) {
                    if (!frequencyLocked || !event.target.closest) return;
                    if (event.target.closest('.frequency-justification-icon')) return;
                    if (!event.target.closest('.frequency-checkbox-container, .js-frequency-checkbox')) return;
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    notice.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, true);
            }
            $(window).on('focus', refresh);
            document.addEventListener('visibilitychange', function () {
                if (!document.hidden) refresh();
            });
            refresh();
        });
    });
}(jQuery));
