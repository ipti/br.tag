/**
 * Generate the content of the form
 * 
 * @param {json} data
 * 
 */
function generateGradesForm(data) {
    if (data !== null && !$.isEmptyObject(data)) {
        $('.select2-container-active').removeClass('select2-container-active');
        $(':focus').blur();
        $(".students ul li").remove();
        $(".grades .tab-content .tab-pane").remove();
        $(".classroom").show();
        console.log(data);
        var stage = data.stage;
        var grades_needed;
        if (stage >= 14 && stage <= 16) {
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
        $.each(data, function (i, v) {
            var id = v.enrollment_id;
            var name = i.length > 25 ? i.substring(0, 22) + "..." : i;
            var full_name = i;
            if (i !== 'stage') {
                addStudentOnMenu(id, name);
                addStudentTable(id, full_name, grades_needed);
                $.each(v.disciplines, function (i, v) {
                    var discipline_id = i;
                    var discipline = v;
                    addStudentGrades(id, discipline_id, discipline, grades_needed);

                });

                addFinalFrequency(id);
                addFrequencyByExam(id,  v.frequencies);
            }
        });
        $(".classroom .students li").first().addClass('active');
        $(".grades .tab-content .tab-pane").first().addClass('active');
    } else {
        $(".classroom").hide();
    }
}


function addStudentOnMenu(id, name) {
    $(".classroom .students ul").append('<li>'
            + '<a href="#tab' + id + '" data-toggle="tab">'
            + '<i></i><span class="grade-student-name">' + name + '</span>'
            + '</a>'
            + '</li>');
}

function addStudentTable(id, name, fields) {
    var notas = "";
    var recuperacao = "";
    var final = fields.final ? '<th class="center" scope="col">Final</th>' : "";
    var numN = fields.notas;
    var numR = fields.recuperacao;
    var numF = fields.final ? 1 : 0;

    var frequency = '<th class="center" scope="col">Aulas Dadas</th>' +
                    '<th class="center" scope="col">Total de Faltas</th>' +
                    '<th class="center" scope="col">Frequência</th>';

    var media = '<th class="center" scope="col">Media Anual</th>' +
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
            + '<table class="grade-table table table-bordered table-striped">'
            + '<col>'
            + '<colgroup span="4"></colgroup>'
            + '<colgroup span="4"></colgroup>'
            + '<thead>'
            + '<tr>'
            + '<td rowspan="2"></td>'
            + '<th colspan="' + numN + '" scope="colgroup" class="center">Avaliações</th>'
            + ((numR + numF) > 0 ? '<th colspan="' + (numR + numF) + '" scope="colgroup" class="center">Recuperações</th>' : "")
            + '<th colspan="2" scope="colgroup" class="center">Médias</th>'
            + '<th colspan="3" scope="colgroup" class="center">Frequência</th>'
            + '</tr>'
            + '<tr>'
            + notas
            + recuperacao
            + final
            + media
            + frequency
            + '</tr>'
            + '</thead>'
            + '<tbody class="row-grades"></tbody>'
            + '</table>'
            + '</div>');
}

function addStudentGrades(id, discipline_id, discipline, fields) {
    var tbody = "<tr>";
    var numN = fields.notas;
    var numR = fields.recuperacao;
    var isCycle = fields.cycle;
    tbody += '<td class="discipline-name">' + discipline.name + '</td>';
    for (var i = 0; i < numN; i++) {
        if (!isCycle) {
            tbody += '<td class="center"><input name="grade[' + id + '][' + discipline_id + '][' + i + ']" '
                    + 'class="grade" type="number" step="0.1" min="0" max="10.0" value="' + discipline["n" + (i + 1)] + '" /></td>"';
        } else {
            tbody += '<td class="center"><select name="grade[' + id + '][' + discipline_id + '][' + i + ']" '
                    + 'class="grade-dropdown">'
                    + '<option value=""></option>'
                    + '<option value="0" ' + ((discipline["n" + (i + 1)] === "0") ? 'selected' : '') + '>Insatisfatório</option>'
                    + '<option value="5" ' + ((discipline["n" + (i + 1)] === "5") ? 'selected' : '') + '>Satisfatório</option>'
                    + '<option value="10" ' + ((discipline["n" + (i + 1)] === "10") ? 'selected' : '') + '>Muito Satisfatório</option>'
                    + '</select></td>"';
        }
    }
    for (var i = numN; i < numR + numN; i++) {
        tbody += '<td class="center"><input name="grade[' + id + '][' + discipline_id + '][' + i + ']" '
                + 'class="grade" type="number" step="0.1" min="0" max="10.0" value="' + discipline["r" + (i - 3)] + '" /></td>"';
    }
    tbody += fields.final ? ('<td class="center"><input name="grade[' + id + '][' + discipline_id + '][8]" '
            + 'class="grade" type="number" step="0.1" min="0" max="10.0" value="' + discipline["rf"] + '" /></td>"') : "";


    var avgfq = ["annual_average", "final_average", "school_days", "absences", "frequency"];

    for (var i = 0; i < 2; i++) {
        tbody += '<td class="center"><input name="avgfq[' + id + '][' + discipline_id + '][' + i + ']"'
            + 'class="average-fields" type="number" step="1" min="0" max="1000" value="' + discipline[avgfq[i]] + '" /></td>"';
    }

    for (var i = 0; i < 3; i++) {
        var j = i + 2;
        tbody += '<td class="center"><input name="avgfq[' + id + '][' + discipline_id + '][' + j + ']" '
            + 'class="frequency-fields" type="number" step="1" min="0" max="1000" value="' + discipline[avgfq[j]] + '"  /></td>"';
    }
    tbody += "</tr>";
    $('#tab' + id).find(".grade-table .row-grades").append(tbody);
}

function addFinalFrequency(id) {
    var tbody = "<tr>";
    tbody += '<td class="discipline-name">Dias Letivos</td>';
    for (var i = 0; i < 4; i++) {
        tbody += '<td class="center"><input name="exams[' + id + '][0][' + i + ']" '
            + 'class="frequency-fields" type="number" step="1" min="0" max="1000" value="" /></td>"';
    }
    tbody += "</tr>";
    tbody += "<tr>";
    tbody += '<td class="discipline-name">Carga Horária</td>';
    for (var i = 0; i < 4; i++) {
        tbody += '<td class="center"><input name="exams[' + id + '][1][' + i + ']" '
            + 'class="frequency-fields" type="number" step="1" min="0" max="1000" value="" /></td>"';
    }
    tbody += "</tr>";
    $('#tab' + id).find(".grade-table .row-grades").append(tbody);
}

function addFrequencyByExam (id, frequencies){
    var tbody = "<tr>";
    tbody += '<td class="discipline-name">Número de faltas</td>';
    for (var i = 0; i < 4; i++) {
        tbody += '<td class="center"><input name="exams[' + id + '][2][' + i + ']" '
            + 'class="frequency-fields" type="number" step="1" min="0" max="1000" value="' + frequencies[i] + '"/></td>"';
    }
    tbody += "</tr>";
    $('#tab' + id).find(".grade-table .row-grades").append(tbody);
}