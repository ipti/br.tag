var correctIntervalDate = false;

$('.initial-date, .final-date').mask("99/99/9999");
$(".initial-date").datepicker({
    language: "pt-BR",
    format: "dd/mm/yyyy",
    autoclose: true,
    todayHighlight: true,
    allowInputToggle: true,
    disableTouchKeyboard: true,
    keyboardNavigation: false,
    orientation: "bottom left",
    clearBtn: true,
    maxViewMode: 2,
    startDate: "01/01/" + $(".school-year").val(),
    endDate: "31/12/" + $(".school-year").val()
}).on('changeDate', function (ev, indirect) {
    if ($(".initial-date").val() !== "" && $(".initial-date").val().length == 10
        && $(".final-date").val() !== "" && $(".final-date").val().length == 10) {
        var startDateStr = $(".initial-date").val().split("/");
        var startDate = !indirect ? new Date(ev.date.getFullYear(), ev.date.getMonth(), ev.date.getDate(), 0, 0, 0) : new Date(startDateStr[2], startDateStr[1] - 1, startDateStr[0], 0, 0, 0);
        var endDateStr = $(".final-date").val().split("/");
        var endDate = new Date(endDateStr[2], endDateStr[1] - 1, endDateStr[0], 0, 0, 0);
        if (endDate < startDate) {
            correctIntervalDate = false;
        } else {
            correctIntervalDate = true;
        }
    } else {
        correctIntervalDate = false;
    }
}).on('clearDate', function (ev) {
    correctIntervalDate = false;
});

$(".final-date").datepicker({
    language: "pt-BR",
    format: "dd/mm/yyyy",
    autoclose: true,
    todayHighlight: true,
    allowInputToggle: true,
    disableTouchKeyboard: true,
    keyboardNavigation: false,
    orientation: "bottom left",
    clearBtn: true,
    maxViewMode: 2,
    startDate: "01/01/" + $(".school-year").val(),
    endDate: "31/12/" + $(".school-year").val()
}).on('changeDate', function (ev) {
    if ($(".initial-date").val() !== "" && $(".initial-date").val().length == 10
        && $(".final-date").val() !== "" && $(".final-date").val().length == 10) {
        var endDate = new Date(ev.date.getFullYear(), ev.date.getMonth(), ev.date.getDate(), 0, 0, 0);
        var startDateStr = $(".initial-date").val().split("/");
        var startDate = new Date(startDateStr[2], startDateStr[1] - 1, startDateStr[0], 0, 0, 0);
        if (endDate < startDate) {
            correctIntervalDate = false;
        } else {
            correctIntervalDate = true;
        }
    } else {
        correctIntervalDate = false;
    }
}).on('clearDate', function (ev) {
    correctIntervalDate = false;
});

$(document).on("change", "#report", function () {
    if ($(this).val() !== "") {
        if ($("#report").val() === "frequency") {
            $(".classroom-container, .date-container").show();
            $(".students-container").hide();
            if ($("#classroom").val() !== "" && $("#classroom > option:selected").attr("fundamentalMaior") === "1") {
                $(".disciplines-container").show();
            }
        } else if ($("#report").val() === "gradesByStudent") {
            $(".classroom-container").show();
            $(".date-container, .disciplines-container").hide();
            if ($("#classroom").val() !== "") {
                $(".students-container").show();
            }
        }
        $(".dependent-filters").show();
    } else {
        $(".dependent-filters").hide();
    }
});

$("#classroom").on("change", function () {
    $("#discipline, #student").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        if ($("#classroom > option:selected").attr("fundamentalMaior") === "1") {
            $.ajax({
                type: "POST",
                url: "?r=reports/getDisciplines",
                cache: false,
                data: {
                    classroom: $("#classroom").val(),
                },
                beforeSend: function () {
                    $("#discipline").attr("disabled", "disabled");
                },
                success: function (response) {
                    if (response === "") {
                        $("#discipline").html("<option value='-1'></option>").trigger("change.select2").show();
                    } else {
                        $("#discipline").html(decodeHtml(response)).trigger("change.select2").show();
                    }
                    if ($("#report").val() === "frequency") {
                        $(".disciplines-container").show();
                    }
                    $("#discipline").removeAttr("disabled");
                },
            });
        } else {
            $(".disciplines-container").hide();
        }

        $.ajax({
            type: "POST",
            url: "?r=reports/getEnrollments",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
            },
            beforeSend: function () {
                $("#student").attr("disabled", "disabled");
            },
            success: function (response) {
                if (response === "") {
                    $("#student").html("<option value='-1'></option>").trigger("change.select2").show();
                } else {
                    $("#student").html(decodeHtml(response)).trigger("change.select2").show();
                }
                if ($("#report").val() === "gradesByStudent") {
                    $(".students-container").show();
                }
                $("#student").removeAttr("disabled");
            },
        });
    } else {
        $(".disciplines-container, .students-container").hide();
    }
});

$(document).on("click", "#loadreport", function () {
    $(".alert-report").hide();
    var valid = false;
    switch ($("#report").val()) {
        case "frequency":
            var isFundamentalMaior = $("#classroom option:selected").attr("fundamentalmaior") === "1";
            if ($("#classroom").val() !== "" && (!isFundamentalMaior || $("#discipline").val() !== "") && correctIntervalDate) {
                valid = true;
            }
            break;
        case "gradesByStudent":
            if ($("#classroom").val() !== "" && $("#student").val() !== "") {
                valid = true;
            }
            break;
    }
    if (valid) {
        loadReport();
    } else {
        $(".alert-report").text("Preencha os campos obrigatórios corretamente.").show();
    }
});

function loadReport() {
    $.ajax({
        type: "POST",
        url: "?r=reports/generateElectronicDiaryReport",
        cache: false,
        data: {
            type: $("#report").val(),
            classroom: $("#classroom").val(),
            fundamentalMaior: $("#classroom option:selected").attr("fundamentalmaior"),
            discipline: $("#discipline").val(),
            initialDate: $(".initial-date").val(),
            finalDate: $(".final-date").val(),
            student: $("#student").val()
        },
        beforeSend: function () {
            $(".loading-report").css("display", "inline-block");
            $(".report-container, #loadreport").css("opacity", 0.3).css("pointer-events", "none");
            $("#report, #classroom, #discipline, #student, .initial-date, .final-date, .print-report").attr("disabled", "disabled");
        },
        success: function (data) {
            data = JSON.parse(data);
            var html = "";
            if ($("#report").val() === "frequency") {
                html += "" +
                    "<table class='frequency-table table table-bordered table-striped table-hover'>" +
                    "<thead>" +
                    "<tr><th class='table-title' colspan='5'>Frequência</th></tr>" +
                    "<tr><th class='center' colspan='5'>"
                    + $('#classroom').select2('data').text + " - "
                    + ($("#classroom option:selected").attr("fundamentalmaior") === "1" ? $('#discipline').select2('data').text + " - " : "")
                    + $(".initial-date").val() + " a " + $(".final-date").val()
                    + "</th></tr>" +
                    "<tr><th>Nome</th><th>" + ($("#classroom option:selected").attr("fundamentalmaior") === "1" ? "Aulas" : "Dias Letivos") + "</th><th>Faltas</th><th>Frequência</th><th>Faltas (Dias)</th></tr>" +
                    "</thead>" +
                    "<tbody>";
                $.each(data.students, function (i, student) {
                    var faultDaysContainer = "";
                    $.each(this.faults, function (j, faultDays) {
                        faultDaysContainer += faultDays + (j < Object.keys(student.faults).length - 1 ? "; " : "");
                    });
                    html += "<tr><td>" + student.name + "</td><td>" + student.total + "</td><td>" + Object.keys(student.faults).length + "</td><td>" + student.frequency + "</td><td>" + faultDaysContainer + "</td></tr>";
                });
                html += "</tbody></table>";
                $(".report-container").html(html);
                $(".print-report").show();
            } else if ($("#report").val() === "gradesByStudent") {
                if (data.valid) {
                    html += "" +
                        "<table class='grades-by-student-table table table-bordered table-striped table-hover'>" +
                        "<thead>";
                    var totalColSpan = 0;
                    var unityRow = "";
                    $.each(data.unityNames, function () {
                        totalColSpan += this.colspan;
                        unityRow += "<th class='center' colspan='" + this.colspan + "'>" + this.name + "</th>";
                    });
                    var subunityRow = "";
                    $.each(data.subunityNames, function () {
                        subunityRow += "<th class='center'>" + this + "</th>"
                    });
                    totalColSpan += (!data.isUnityConcept ? 3 : 2);
                    html += "<tr><th class='table-title' colspan='" + totalColSpan + "'>Notas</th></tr>";
                    html += "<tr><th class='center' colspan='" + totalColSpan + "'>" + $('#student').select2('data').text + "</th></tr>";
                    html += "<tr><th class='center' colspan='" + totalColSpan + "'>" + $('#classroom').select2('data').text + "</th></tr>";
                    html += "<tr><th></th>" + unityRow;
                    html += !data.isUnityConcept ? "<th class='center'></th><th></th>" : "<th></th>";
                    html += "</tr>";
                    html += "<tr><th>Disciplina</th>" + subunityRow;
                    html += !data.isUnityConcept ? "<th class='center'>Média</th>" : "";
                    html += "<th class='center'>Situação</th>";
                    html += "</tr></thead><tbody>";
                    $.each(data.rows, function () {
                        html += "<tr><td>" + this.disciplineName + "</td>";
                        $.each(this.grades, function () {
                            html += "<td class='center'>" + this.unityGrade + "</td>";
                            if (this.gradeUnityType === "UR") {
                                html += "<td class='center'>" + this.unityRecoverGrade + "</td>";
                            }
                        });
                        html += !data.isUnityConcept ? "<td class='center'>" + this.finalMedia + "</td>" : "";
                        html += "<td class='center'>" + this.situation + "</td></tr>";
                    });
                    html += "</tbody></table>";
                    $(".report-container").html(html);
                    $(".print-report").show();
                } else {
                    $(".alert-report").text("Ainda não foi construída uma estrutura de unidades e avaliações para esta turma.").show();
                }
            }
        },
        complete: function (response) {
            $(".loading-report").hide();
            $(".report-container, #loadreport").css("opacity", 1).css("pointer-events", "auto");
            $("#report, #classroom, #discipline, #student, .initial-date, .final-date, .print-report").removeAttr("disabled");
        },
    });
}

$(document).on("click", ".print-report", function () {
    var popup = window.open('', '', 'toolbar=no, menubar=no');
    popup.document.writeln('<!DOCTYPE html>');
    popup.document.writeln('<html moznomarginboxes mozdisallowselectionprint><head><title>Relatório</title>');
    popup.document.writeln('<link rel="stylesheet" type="text/css" media="print" href="./css/reports/prints/print-reports.css?v=1.1">');
    popup.document.writeln('<div class="header">' + $(".report-header")[0].innerHTML + '</div>');
    popup.document.writeln('<h3>Diário Eletrônico</h3>');
    popup.document.writeln("<div class='body-container'>" + $(".report-container")[0].innerHTML + "</div>");
    popup.document.writeln('<div class="footer">' + $(".report-footer")[0].innerHTML + '</div>');
    popup.document.writeln('</div>');
    popup.document.writeln('</body>');
    popup.document.writeln('</html>');
    popup.document.close();
    popup.onload = function () {
        setTimeout(function () {
            popup.focus();
            popup.print();
            popup.close();
        }, 200);
    };
});