/**
 * Generate the content of the form
 *
 * @param {json} data
 *
 */
function generateGradesForm(data) {
    if (data !== null && !$.isEmptyObject(data.students)) {
        $(".alert-no-students").hide();
        $('.select2-container-active').removeClass('select2-container-active');
        $(':focus').blur();
        $(".students ul li").remove();
        $(".grades .tab-content .tab-pane").remove();
        $(".classroom").show();
        var grades_needed;
        if (data.isConcept) {
            grades_needed = {
                notas: 4,
                recuperacao: 0,
                final: false,
                cycle: true
            };
        } else {
            grades_needed = {
                notas: 4,
                recuperacao: 4,
                final: true,
                cycle: false
            };
        }
        $.each(data.students, function (i, v) {
            var id = v.enrollment_id;
            var name = i.length > 21 ? i.substring(0, 18) + "..." : i;
            var full_name = i;
            addStudentOnMenu(id, name);
            addStudentTable(data, id, full_name, grades_needed);
            $(".no-disciplines-guide").show();
            $.each(v.disciplines, function (i, v) {
                var discipline_id = i;
                var discipline = v;
                addStudentGrades(data, id, discipline_id, discipline, grades_needed);
            });
            addFieldsByFrequency(id, v.school_days, v.workload, grades_needed);
            addFrequencyByExam(id, v.frequencies, grades_needed);
            data.isInstructor ? $(".shared-rows").hide() : "";
        });
        $("#save").show();
        $(".classroom .students li").first().addClass('active');
        $(".grades .tab-content .tab-pane").first().addClass('active');
    } else {
        $(".alert-no-students").show();
    }

    var classList = [];

    $('*[class^="school-days-group"]').each(function () {
        if (this.className != "" && classList.indexOf(this.className) == -1) {
            $("." + this.className).blur(function () {
                $("." + this.className).val(this.value);
            });
        }
    });

    $('*[class^="school-days-group"]').focus(function () {
        $(this).css("border", "1px solid orange");
    });

    $('*[class^="school-days-group"]').blur(function () {
        $(this).css("border", "1px solid #ccc");
    });


    var classList = [];

    $('*[class^="dias-letivos"]').each(function () {
        if (this.className != "" && classList.indexOf(this.className) == -1) {
            $("." + this.className).blur(function () {
                $("." + this.className).val(this.value);
            });
        }
    });

    $('*[class^="dias-letivos"]').focus(function () {
        $(this).css("border", "1px solid orange");
    });

    $('*[class^="dias-letivos"]').blur(function () {
        $(this).css("border", "1px solid #ccc");
    });

    var classList = [];

    $('*[class^="carga-horaria"]').each(function () {
        if (this.className != "" && classList.indexOf(this.className) == -1) {
            $("." + this.className).blur(function () {
                $("." + this.className).val(this.value);
            });
        }
    });

    $('*[class^="carga-horaria"]').focus(function () {
        $(this).css("border", "1px solid orange");
    });

    $('*[class^="carga-horaria"]').blur(function () {
        $(this).css("border", "1px solid #ccc");
    });

    $('[data-toggle="popover"]').popover({ trigger: "hover" });

    $(".students ul li").click(function () {
        $('html,body').animate({
            scrollTop: $("#classes-form").offset().top
        },
            'slow');
    });

}


function addStudentOnMenu(id, name) {
    $(".classroom .students ul").append('<li>'
        + '<a href="#tab' + id + '" data-toggle="tab">'
        + '<i></i><span class="grade-student-name">' + name + '</span>'
        + '</a>'
        + '</li>');
}

function addStudentTable(data, id, name, fields) {
    var notas = "";
    var recuperacao = "";
    var final = fields.final ? '<th class="center" scope="col">Final</th>' : "";
    var numN = fields.notas;
    var numR = fields.recuperacao;
    var numF = fields.final ? 1 : 0;

    var frequency = '<th colspan="3" scope="colgroup" class="center title_table_notes">Frequência</th>';
    var media = fields.cycle ? "" : '<th colspan="2" scope="colgroup" class="center title_table_notes">Médias</th>';
    var fields_frequency = '<th data-toggle="tooltip" data-placement="left" title="Tooltip on left" class="center grades_title_padding" scope="col">Aulas Dadas</th> ' +
        '<th class="center grades_title_padding" scope="col">Total de Faltas</th>' +
        '<th class="center grades_title_padding" scope="col">Frequência %</th>';

    var fields_media = fields.cycle ? "" : '<th class="center" scope="col">Media Anual</th>' +
        '<th class="center" scope="col">Média Final</th>';

    for (var i = 1; i <= numN; i++) {
        notas += '<th class="center" scope="col">' + i + 'ª</th>';
    }
    for (var i = 1; i <= numR; i++) {
        recuperacao += '<th class="center recuperacao" scope="col">' + i + 'ª</th>';
    }


    $(".grades .tab-content").append(
        '<div class="tab-pane" id="tab' + id + '">'
        + '<h6>' + name + '</h6>'
        + '<table class="grade-table table table-bordered table-grades' + (data.isInstructor ? "" : "table-striped") + '">'
        + '<col>'
        + '<colgroup span="3"></colgroup>'
        + '<colgroup span="3"></colgroup>'
        + '<thead>'
        + '<tr>'
        + '<td rowspan="2"></td>'
        + '<th colspan="' + numN + '" scope="colgroup" class="center title_table_notes">Avaliações</th>'
        + ((numR + numF) > 0 ? '<th colspan="' + (numR + numF) + '" scope="colgroup" class="center title_table_notes">Recuperações</th>' : "")
        + media
        + frequency
        + '</tr>'
        + '<tr>'
        + notas
        + recuperacao
        + final
        + fields_media
        + fields_frequency
        + '</tr>'
        + '</thead>'
        + '<tbody class="row-grades"></tbody>'
        + '</table>'
        + '</div>');
}

function addStudentGrades(data, id, discipline_id, discipline, fields) {
    var tbody = "<tr class='" + (data.isInstructor && !Number(discipline.isInstructorsDiscipline) ? "no-show" : "") + "'>";
    var numN = fields.notas;
    var numR = fields.recuperacao;
    var isCycle = fields.cycle;
    tbody += '<td class="discipline-name">' + discipline.name + '</td>';
    for (var i = 0; i < numN; i++) {
        if (!isCycle) {
            tbody += '<td class="center"><input name="grade[' + id + '][' + discipline_id + '][' + i + ']" '
                + 'class="grade grades_inputs" type="text" step="0.1" min="0" max="10.0" value="' + discipline["n" + (i + 1)] + '" /></td>"';
        } else {
            tbody += '<td class="center"><select name="grade[' + id + '][' + discipline_id + '][' + i + ']" '
                + 'class="grades_inputs grade-dropdown">'
                + '<option value=""></option>'
                + '<option value="0" ' + (discipline["n" + (i + 1)] !== "" ? ((Number(discipline["n" + (i + 1)]) >= 0 && Number(discipline["n" + (i + 1)]) < 5) ? 'selected' : '') : '') + '>Insatisfatório</option>'
                + '<option value="5" ' + (discipline["n" + (i + 1)] !== "" ? ((Number(discipline["n" + (i + 1)]) >= 5 && Number(discipline["n" + (i + 1)]) < 10) ? 'selected' : '') : '') + '>Satisfatório</option>'
                + '<option value="10" ' + (discipline["n" + (i + 1)] !== "" ? ((Number(discipline["n" + (i + 1)]) === 10) ? 'selected' : '') : '') + '>Muito Satisfatório</option>'
                + '</select></td>"';
        }
    }
    for (var i = numN; i < numR + numN; i++) {
        tbody += '<td class="center"><input name="grade[' + id + '][' + discipline_id + '][' + i + ']" '
            + 'class="grade grades_inputs" type="text" step="0.1" min="0" max="10.0" value="' + discipline["r" + (i - 3)] + '" /></td>"';
    }
    tbody += fields.final ? ('<td class="center"><input name="grade[' + id + '][' + discipline_id + '][8]" '
        + 'class="grades_inputs grade" type="text" step="0.1" min="0" max="10.0" value="' + discipline["rf"] + '" /></td>"') : "";

    var avgfq = ["annual_average", "final_average", "school_days", "absences", "frequency"];
    if (!isCycle) {
        for (var i = 0; i < 2; i++) {
            tbody += '<td class="center"><input name="avgfq[' + id + '][' + discipline_id + '][' + i + ']"'
                + 'class="average-fields grades_inputs" type="text" step="1" min="0" max="1000" value="' + discipline[avgfq[i]] + '" /></td>"';
        }

    }
    for (var i = 0; i < 3; i++) {
        var j = i + 2;
        var name_of_class = i == 0 ? "school-days-group" + discipline_id : "frequency-fields";
        var popover = i == 0 ? '  data-html = "true" data-container="body" data-toggle="popover" data-placement="right" data-content="<b> Compartilhado por toda a turma </b>"  ' : '';
        tbody += '<td class="center"><input id="" name="avgfq[' + id + '][' + discipline_id + '][' + j + ']" '
            + 'class="grades_inputs  ' + name_of_class + ' " ' + popover + '  type="text" step="1" min="0" max="365" value="' + discipline[avgfq[j]] + '"  /></td>"';
    }
    + 'class="frequency-percentage grades_inputs" type="text" step="0.1" min="0" max="100.0" value="' + discipline[avgfq[4]] + '" /></td>"';
    tbody += "</tr>";
    $('#tab' + id).find(".grade-table .row-grades").append(tbody);

}

function addFieldsByFrequency(id, school_days, workload, fields) {
    var countRemainingColumnsToFill = fields.recuperacao + (fields.final ? 1 : 0) + (fields.cycle ? 0 : 2) + 3;
    var tbody = "<tr class='shared-rows'>";
    tbody += '<td class="discipline-name">Dias Letivos</td>';
    for (var i = 0; i < 4 + countRemainingColumnsToFill; i++) {
        if (i < 4) {
            var classnamed = "dias-letivos" + i;
            tbody += '<td class="center"><input name="exams[' + id + '][0][' + i + ']" '
                + 'class="grades_inputs ' + classnamed + '" type="text"   data-html = "true" data-container="body" data-toggle="popover" data-placement="right" data-content="<b> Compartilhado por toda a turma </b>"  step="1" min="0" max="1000" value="' + school_days[i] + '" /></td>"';
        } else {
            tbody += '<td></td>';
        }
    }
    tbody += "</tr>";
    tbody += "<tr class='shared-rows'>";
    tbody += '<td class="discipline-name">Carga Horária</td>';
    for (var i = 0; i < 4 + countRemainingColumnsToFill; i++) {
        if (i < 4) {
            var classnamed = "carga-horaria" + i;
            tbody += '<td class="center"><input name="exams[' + id + '][1][' + i + ']" '
                + 'class="grades_inputs ' + classnamed + '" type="text"  data-html = "true" data-container="body" data-toggle="popover" data-placement="right" data-content="<b> Compartilhado por toda a turma </b>" step="1" min="0" max="1000" value="' + workload[i] + '" /></td>"';
        } else {
            tbody += '<td></td>';
        }
    }
    tbody += "</tr>";
    $('#tab' + id).find(".grade-table .row-grades").append(tbody);
}

function addFrequencyByExam(id, frequencies, fields) {
    var countRemainingColumnsToFill = fields.recuperacao + (fields.final ? 1 : 0) + (fields.cycle ? 0 : 2) + 3;
    var tbody = "<tr class='shared-rows'>";
    tbody += '<td class="discipline-name">Número de faltas</td>';
    for (var i = 0; i < 4 + countRemainingColumnsToFill; i++) {
        if (i < 4) {
            tbody += '<td class="center"><input name="exams[' + id + '][2][' + i + ']" '
                + 'class="frequency-fields grades_inputs" type="text" step="1" min="0" max="1000" value="' + frequencies[i] + '"/></td>"';
        } else {
            tbody += '<td></td>';
        }
    }
    tbody += "</tr>";
    $('#tab' + id).find(".grade-table .row-grades").append(tbody);
}