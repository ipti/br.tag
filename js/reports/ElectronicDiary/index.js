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
        $(".dependent-filters").show();
    } else {
        $(".dependent-filters").hide();
    }
});

$("#classroom").on("change", function () {
    $("#discipline").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        if ($("#classroom > option:selected").attr("fundamentalMaior") === "1") {
            $.ajax({
                type: "POST",
                url: "?r=reports/getDisciplines",
                cache: false,
                data: {
                    classroom: $("#classroom").val(),
                },
                success: function (response) {
                    if (response === "") {
                        $("#discipline").html("<option value='-1'></option>").trigger("change.select2").show();
                    } else {
                        $("#discipline").html(decodeHtml(response)).trigger("change.select2").show();
                    }
                    $(".disciplines-container").show();
                },
            });
        } else {
            $(".disciplines-container").hide();
        }
    } else {
        $(".disciplines-container").hide();
    }
});

$(document).on("click", "#loadreport", function () {
    $(".alert-required-fields").hide();
    var valid = false;
    switch ($("#report").val()) {
        case "frequency":
            var isFundamentalMaior = $("#classroom option:selected").attr("fundamentalmaior") === "1";
            if ($("#classroom").val() !== "" && (!isFundamentalMaior || $("#discipline").val() !== "") && correctIntervalDate) {
                valid = true;
            }
            break;
    }
    if (valid) {
        loadReport();
    } else {
        $(".alert-required-fields").show();
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
        },
        beforeSend: function () {
            $(".loading-report").css("display", "inline-block");
            $(".report-container").css("opacity", 0.3).css("pointer-events", "none");
            $("#report, #classroom, #discipline, .initial-date, .final-date, #loadreport, .print-report").attr("disabled", "disabled");
        },
        success: function (data) {
            data = JSON.parse(data);
            var html = "";
            if ($("#report").val() === "frequency") {
                html += "" +
                    "<table class='frequency-table table table-bordered table-striped table-hover'>" +
                    "<thead>" +
                    "<tr><th class='table-title' colspan='4'>Frequência</th></tr>" +
                    "<tr><th class='table-subtitle' colspan='4'>"
                    + $('#classroom').select2('data').text + " - "
                    + ($("#classroom option:selected").attr("fundamentalmaior") === "1" ? $('#discipline').select2('data').text + " - " : "")
                    + $(".initial-date").val() + " a " + $(".final-date").val()
                    + "</th></tr>" +
                    "<tr><th>Nome</th><th>" + ($("#classroom option:selected").attr("fundamentalmaior") === "1" ? "Aulas" : "Dias") + "</th><th>Faltas</th><th>Frequência</th></tr>" +
                    "</thead>" +
                    "<tbody>";
                $.each(data.students, function () {
                    html += "<tr><td>" + this.name + "</td><td>" + this.total + "</td><td>" + this.faults + "</td><td>" + this.frequency + "</td></tr>";
                });
                html += "</tbody></table>";
                $(".report-container").html(html);
                $(".print-report").show();
            }
        },
        complete: function (response) {
            $(".loading-report").hide();
            $(".report-container").css("opacity", 1).css("pointer-events", "auto");
            $("#report, #classroom, #discipline, .initial-date, .final-date, #loadreport, .print-report").removeAttr("disabled");
        },
    });
}

$(document).on("click", ".print-report", function () {
    var popup = window.open('', '', 'toolbar=no, menubar=no');
    popup.document.writeln('<!DOCTYPE html>');
    popup.document.writeln('<html moznomarginboxes mozdisallowselectionprint><head><title>Relatório</title>');
    popup.document.writeln('<link rel="stylesheet" type="text/css" media="print" href="./css/reports/prints/print-reports.css?v=1.0">');
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