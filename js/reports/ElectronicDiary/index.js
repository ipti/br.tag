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

$(document).on("change", "#report", function() {
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
            $("#report, #classroom, #discipline, .initial-date, .final-date, #loadreport").attr("disabled", "disabled");
        },
        success: function (data) {
            data = JSON.parse(data);
            console.log(data);
        },
        complete: function (response) {
            $(".loading-report").hide();
            $(".report-container").css("opacity", 1).css("pointer-events", "auto");
            $("#report, #classroom, #discipline, .initial-date, .final-date, #loadreport").removeAttr("disabled");
        },
    });
}