function load() {
    $(".alert-incomplete-data").hide();
    const fundamentalMaior = Number($("#classroom option:selected").attr("fundamentalmaior"));
    const monthSplit = $("#month").val().split("-");
    jQuery.ajax({
        type: "POST",
        url: "?r=classes/getFrequency",
        cache: false,
        data: {
            classroom: $("#classroom").val(),
            discipline: $("#disciplines").val(),
            fundamentalMaior: fundamentalMaior,
            month: monthSplit[1],
            year: monthSplit[0]
        },
        beforeSend: function () {
            $(".loading-frequency").css("display", "inline-block");
            $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
            $("#classroom, #month, #disciplines, #classesSearch").attr("disabled", "disabled");
        },
        success: function (response) {
            let data = JSON.parse(response);
            if (data.valid) {
                let html = "";
                html +=
                    `
                        <div class='container-frequency-over'>
                        <table class='table-frequency table table-bordered table-striped table-hover'>
                        <thead class='t-accordion__head'>
                        <tr>
                        </tr>`;
                let daynameRow = "";
                let dayRow = "";
                let scheduleRow = "";
                let checkboxRow = "";

                $.each(data.students[0].schedules, function () {
                    dayRow += "<th>" + (pad(this.day, 2) + "/" + pad(monthSplit[1], 2)) + "/" + monthSplit[0] + "</th>";

                    checkboxRow += "<th class='frequency-checkbox-general frequency-checkbox-container " + (!this.available ? "disabled" : "") + "'><input class='frequency-checkbox' type='checkbox' " + (!this.available ? "disabled" : "") + " classroomId='" + $("#classroom").val() + "' day='" + this.day + "' month='" + monthSplit[1] + "' year='" + monthSplit[0] + "' schedule='" + this.schedule + "'></th>";
                });
                html += "<tr class='day-row sticky'><th class='sticky-column'></th>" + dayRow + "<tr class='checkbox-row'><th class='checkbox-row sticky-column'></th>" + checkboxRow + "</tr>";
                html += "</thead></div><tbody class='t-accordion__body'>";
                $.each(data.students, function (indexStudent, student) {

                    html += "<tr><td class='student-name sticky-column'>" + buildEnrollmentStatusLabel(student.status, student.statusLabel) + student.studentName + "</td>";
                    $.each(student.schedules, function (indexSchedule, schedule) {
                        var justificationContainer = "";
                        if (schedule.fault) {
                            if (schedule.justification !== null) {
                                justificationContainer += "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon' title='" + schedule.justification + "'><i class='fa fa-file-text-o'></i><i class='fa fa-file-text'></i></a>";
                            } else {
                                justificationContainer += "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'><i class='fa fa-file-o'></i><i class='fa fa-file'></i></a>";
                            }
                        }

                        html += "<td class='frequency-checkbox-student frequency-checkbox-container " + (!this.available || !schedule.valid ? "disabled" : "") + "'><input class='frequency-checkbox' type='checkbox' " + (!schedule.available || !schedule.valid ? "disabled" : "") + " " + (schedule.fault ? "checked" : "") + " classroomId='" + $("#classroom").val() +
                            "' studentId='" + student.studentId + "' day='" + schedule.day + "' month='" + monthSplit[1] + "' year='" + monthSplit[0] + "' schedule='" + schedule.schedule + "' fundamentalMaior='" + fundamentalMaior + "'>" + justificationContainer + "</td>";
                    });
                    html += "</tr>";
                });
                html += "</tbody></table>";
                $("#frequency-container").html(html).show();
                $(".frequency-checkbox-general").each(function () {
                    var day = $(this).find(".frequency-checkbox").attr("day");
                    $(this).find(".frequency-checkbox").prop("checked", $(".frequency-checkbox-student .frequency-checkbox[day=" + day + "]:checked").length === $(".frequency-checkbox-student .frequency-checkbox[day=" + day + "]").length);
                });
                $('[data-toggle="tooltip"]').tooltip({container: "body"});
            } else {
                $("#frequency-container").hide();
                $(".alert-incomplete-data").html(data.error).show();
            }
        },
        complete: function (response) {
            $(".loading-frequency").hide();
            $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
            $("#classroom, #month, #disciplines, #classesSearch").removeAttr("disabled");
        },
    });

}

$(document).on("click", ".frequency-checkbox-container", function (e) {
    if (e.target === this && !$(this).hasClass("disabled") && !$(this).find('input').attr('disabled')) {
        $(this).find(".frequency-checkbox").prop("checked", !$(this).find(".frequency-checkbox").is(":checked")).trigger("change");
    }
});

$("#classroom").on("change", function () {
    $("#frequency-container, .alert-incomplete-data").hide();
    $("#disciplines").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=classes/getMonthsAndDisciplines",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
            },
            beforeSend: function () {
                $(".loading-frequency").css("display", "inline-block");
                $("#classroom, #month, #disciplines").attr("disabled", "disabled");
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.valid) {
                    $("#month").children().remove();
                    $("#month").append(new Option("Selecione o Mês/Ano", ""));
                    $.each(data.months, function (index, value) {
                        $("#month").append(new Option(value.name, value.id));
                    });
                    $("#month option:first").attr("selected", "selected").trigger("change.select2");

                    if (data.isMinor == false) {
                        $("#disciplines").children().remove();
                        $("#disciplines").append(new Option("Selecione a Disciplina", ""));
                        $.each(data.disciplines, function (index, value) {
                            $("#disciplines").append(new Option(value.name, value.id));
                        });
                        $("#disciplines option:first").attr("selected", "selected").trigger("change.select2");
                        $(".disciplines-container").show();
                    } else {
                        $(".disciplines-container").hide();
                    }
                    $(".month-container").show();
                } else {
                    $(".alert-incomplete-data").html(data.error).show();
                    $(".disciplines-container, .month-container").hide();
                }
            },
            complete: function (response) {
                $(".loading-frequency").hide();
                $("#classroom, #month, #disciplines").removeAttr("disabled");
            },
        });
    } else {
        $(".disciplines-container, .month-container").hide();
    }
});

$("#month, #disciplines").on("change", function () {
    if ($("#month").val() !== "" && (!$("#disciplines").is(":visible") || $("#disciplines").val() !== "")) {
        load();
    } else {
        $("#frequency-container, .alert-incomplete-data").hide();
    }
});

$(document).on("change", ".frequency-checkbox", function () {
    var checkbox = this;
    $.ajax({
        type: "POST",
        url: "?r=classes/saveFrequency",
        cache: false,
        data: {
            classroomId: $(this).attr("classroomId"),
            day: $(this).attr("day"),
            month: $(this).attr("month"),
            year: $(this).attr("year"),
            schedule: $(this).attr("schedule"),
            studentId: $(this).attr("studentId"),
            fault: $(this).is(":checked") ? 1 : 0,
        },
        beforeSend: function () {
            $(".loading-frequency").css("display", "inline-block");
            $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
            $("#classroom, #month, #disciplines, #classesSearch").attr("disabled", "disabled");
        },
        complete: function (response) {
            if ($(checkbox).attr("studentId") === undefined) {
                $(".table-frequency tbody .frequency-checkbox[day=" + $(checkbox).attr("day") + "][schedule=" + $(checkbox).attr("schedule") + "]").prop("checked", $(checkbox).is(":checked"));
                if ($(checkbox).is(":checked")) {
                    $(".table-frequency tbody .frequency-checkbox[day=" + $(checkbox).attr("day") + "][schedule=" + $(checkbox).attr("schedule") + "]").each(function () {
                        if (!$(this).parent().find(".frequency-justification-icon").length) {
                            $(this).parent().append("<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'><i class='fa fa-file-o'></i><i class='fa fa-file'></i></a>");
                        }
                    });
                } else {
                    $(".table-frequency tbody .frequency-checkbox[day=" + $(checkbox).attr("day") + "][schedule=" + $(checkbox).attr("schedule") + "]").parent().find(".frequency-justification-icon").remove();
                }
            } else {
                if ($(checkbox).is(":checked")) {
                    $(checkbox).parent().append("<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'><i class='fa fa-file-o'></i><i class='fa fa-file'></i></a>");
                } else {
                    $(checkbox).parent().find(".frequency-justification-icon").remove();

                }
            }
            $(".loading-frequency").hide();
            $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
            $("#classroom, #month, #disciplines, #classesSearch").removeAttr("disabled");
        },
    });
});

$(document).on("click", ".frequency-justification-icon", function () {
    var checkbox = $(this).parent().find(".frequency-checkbox");
    $("#justification-classroomid").val(checkbox.attr("classroomid"));
    $("#justification-studentid").val(checkbox.attr("studentid"));
    $("#justification-day").val(checkbox.attr("day"));
    $("#justification-month").val(checkbox.attr("month"));
    $("#justification-year").val(checkbox.attr("year"));
    $("#justification-schedule").val(checkbox.attr("schedule"));
    $(".justification-text").val($(this).attr("data-original-title"));
    $("#save-justification-modal").modal("show");
});

$("#save-justification-modal").on('shown', function () {
    $(".justification-text").focus();
});

$(document).on("click", ".btn-save-justification", function () {
    $.ajax({
        type: "POST",
        url: "?r=classes/saveJustification",
        cache: false,
        data: {
            classroomId: $("#justification-classroomid").val(),
            studentId: $("#justification-studentid").val(),
            day: $("#justification-day").val(),
            month: $("#justification-month").val(),
            year: $("#justification-year").val(),
            schedule: $("#justification-schedule").val(),
            justification: $(".justification-text").val()
        },
        beforeSend: function () {
            $("#save-justification-modal").find(".modal-body").css("opacity", 0.3).css("pointer-events", "none");
            $("#save-justification-modal").find("button").attr("disabled", "disabled");
            $("#save-justification-modal").find(".centered-loading-gif").show();
        },
        success: function (data) {
            var justification = $(".table-frequency tbody .frequency-checkbox[studentid=" + $("#justification-studentid").val() + "][day=" + $("#justification-day").val() + "][month=" + $("#justification-month").val() + "][year=" + $("#justification-year").val() + "]").parent().find(".frequency-justification-icon");
            console.log(justification)
            if ($(".justification-text").val() == "") {
                justification.html("<i class='fa fa-file-o'></i><i class='fa fa-file'></i>");
                justification.attr("data-original-title", "").tooltip('hide');
            } else {
                justification.html("<i class='fa fa-file-text-o'></i><i class='fa fa-file-text'></i>");
                justification.attr("data-original-title", $(".justification-text").val()).tooltip({container: "body"});
            }
            $("#save-justification-modal").modal("hide");
        },
        complete: function (response) {
            $("#save-justification-modal").find(".modal-body").css("opacity", 1).css("pointer-events", "auto");
            $("#save-justification-modal").find("button").removeAttr("disabled");
            $("#save-justification-modal").find(".centered-loading-gif").hide();
        },
    });
});

$(document).on("keyup", ".justification-text", function (e) {
    if (e.keyCode == 13) {
        $(".btn-save-justification").trigger("click");
    }
});

function buildEnrollmentStatusLabel(status, label){
    return `<label class="t-badge-info t-margin-none--left ${status == 1 ? 'hide' : ''}">
                ${label}
            </label>`;
}
