function createTable(data) {
    $('#given-classes').html('<div class="t-badge-info t-margin-none--left"><b>Total de carga horária do mês: </b>' + data.totalClasses + '</div><div class="t-badge-info t-margin-none--left"><b>Total de aulas ministradas do mês: </b>' + data.totalClassContents + '</div>')
    $('#class-contents > thead').html('<tr><th class="center">Dias</th><th style="text-align:left">Conteúdo ministrado em sala de aula</th></tr>');
    $('#class-contents > tbody').html('');

    $.each(data.classContents, function (day, classContent) {
        let courseClasses = "";
        let isContentsEmpty = false;

        $.each(data.courseClasses, function (index, courseClass) {
            if (classContent.contents && Object.keys(classContent.contents).length) {
                $.each(classContent.contents, function (index, content) {
                    if(courseClass.id == content) {
                        courseClasses += '<div class="t-badge-info column text-align--left t-padding-medium--x" style="width:80%">'
                        + "<div>"
                        + "<b>Aula:</b> " + courseClass.order + " <b>Plano de aula:</b> " + courseClass.cpname + "<br>"
                        + "<b>Conteúdo:</b> " + courseClass.content
                        + "</div>"
                        + '</div>';
                    }
                });
            } else {
                isContentsEmpty =  true;
            }
        });

        if(isContentsEmpty) {
            courseClasses += '<div class="t-badge-info column text-align--left t-padding-medium--x">'
            + 'Nenhuma aula ministrada neste dia'
            + '</div>';
        }


        let head = '<th class="center vmiddle contents-day ">' + ((day < 10) ? '0' : '') + day + '</th>';
        let body = '<td class="row align-items--center">'
            + '<div class="column clearfix" id="day[' + day + ']" name="day[' + day + '][]">'
            + courseClasses
            + '</div>'
            + '</td>';
        $('#class-contents > tbody').append('<tr class="center day-row" day="' + day + '">' + head + body + '</tr>');
    });
    $('#widget-class-contents').show();
    $('#class-contents').show();
}
