$(document).on("change", "#classroom_fk", function () {
    var select = this;
    $(select).attr("disabled", "disabled");
    $.ajax({
        url: getTimesheetURL,
        type: "POST",
        data: {
            cid: $(this).val()
        },
        beforeSend: function () {
            $(".alert").addClass("display-hide");
            $(".loading-timesheet").css("display", "inline-block");
            $(".table-container").css("opacity", 0.3).css("pointer-events", "none");
            $(".btn-generate-timesheet").attr("disabled", "disabled");
        },
    }).success(function (result) {
        getTimesheet(result);
    }).complete(function () {
        $(".loading-timesheet").hide();
        $(".table-container").css("opacity", 1).css("pointer-events", "auto");
        $(select).removeAttr("disabled");
        $(".btn-generate-timesheet").removeAttr("disabled");
    });
});

function getTimesheet(data) {
    data = $.parseJSON(data);
    $(".alert").addClass("display-hide");
    $(".schedule-info").removeClass("display-hide");
    $("#turn").hide();
    $(".table-container").hide();
    if (data.valid == null) {
        $(".schedule-info").addClass("display-hide");
    } else if (!data.valid) {
        if (data.error == "curricularMatrix" || data.error == "calendar") {
            $(".alert").removeClass("display-hide");
            $(".schedule-info").addClass("display-hide");
        }
    } else {
        $(".table-container").show();
        $("#turn").show();
        buildTimesheet(data.schedules);
    }
}

$(document).on("click", ".btn-generate-timesheet", function () {
    if ($(".tables-timesheet").is(":visible") && $(".table-month tbody td").children().length) {
        $("#generateAnotherTimesheet").modal("show");
    } else {
        generateTimesheet();
    }
});

$(document).on("click", ".confirm-timesheet-generation", function () {
    generateTimesheet();
});

function generateTimesheet() {
    var classroom = $("select.classroom-id").val();
    $.ajax({
        url: generateTimesheetURL,
        type: "POST",
        data: {
            classroom: classroom
        },
        beforeSend: function () {
            $(".loading-timesheet").css("display", "inline-block");
            $(".table-container").css("opacity", 0.3).css("pointer-events", "none");
        },
    }).success(function (result) {
        getTimesheet(result);
    }).complete(function () {
        $(".loading-timesheet").hide();
        $(".table-container").css("opacity", 1).css("pointer-events", "auto");
    });
}

function buildTimesheet(data) {
    $(".tables-timesheet tbody tr td").children().remove();
    var turn = "";
    $.each(data, function (month, days) {
        $.each(days, function (day, schedules) {
            $.each(schedules, function (schedule, info) {
                if (turn === "") {
                    if (info.turn == 0) turn = "Manhã";
                    if (info.turn == 1) turn = "Tarde";
                    if (info.turn == 2) turn = "Noite";
                }
                var discipline = changeNameLength(info.disciplineName, 30);
                // var instructor = changeNameLength(info.instructorInfo.name, 30);

                // var icons = "";
                // if (info.instructorInfo.unavailable)
                //     icons +=
                //         "<i title='Horário indisponível para o instrutor.' class='unavailability-icon fa fa-times-circle darkred'></i>";
                // if (info.instructorInfo.countConflicts > 1)
                //     icons +=
                //         "<i title='Instrutor possui " +
                //         info.instructorInfo.countConflicts +
                //         " conflitos neste horário.' class='fa fa-exclamation-triangle conflict-icon darkgoldenrod'></i>";
                $(".tables-timesheet table[month=" + month + "] tbody tr[schedule=" + schedule + "] td[day=" + day + "]").html(
                    "<div schedule='" + info.id + "' class='schedule-block'>" +
                    "<p class='discipline-name' discipline_id='" + info.disciplineId + "' title='" + info.disciplineName + "'>" + discipline + "</p>" +
                    // "<p class='instructor-name' instructor_id='" + info.instructorInfo.id + "' title='" + info.instructorInfo.name + "'>" +
                    // instructor +
                    // "<i class='fa fa-pencil edit-instructor'></i></p>" +
                    // icons +
                    "</div>"
                );
            });
        });
    });
    $("#turn").text(turn);
}

function changeNameLength(name, limit) {
    return name.length > limit ? name.substring(0, limit - 3) + "..." : name;
}

$(document).on("click", ".tables-timesheet td", function () {
    if ($(this).hasClass("schedule-selected")) {
        $(this).removeClass("schedule-selected");
        $(".tables-timesheet").find(".schedule-available").removeClass("schedule-available");
    } else {
        //Já selecionou algum diferente do primeiro e da mesma semana
        if ($(".tables-timesheet").find(".schedule-selected").length > 0) {
            var firstSelected = $(".tables-timesheet").find(".schedule-selected");
            var secondSelected = $(this);

            if ((!firstSelected.find(".schedule-block").length && !secondSelected.find(".schedule-block").length) || !secondSelected.hasClass("schedule-available")) {
                firstSelected.removeClass("schedule-selected");
                $(".tables-timesheet").find(".schedule-available").removeClass("schedule-available");
            } else {
                var firstSchedule = {
                    month: firstSelected.closest("table").attr("month"),
                    day: firstSelected.attr("day"),
                    week_day: firstSelected.attr("week_day"),
                    schedule: firstSelected.closest("tr").attr("schedule")
                };
                var secondSchedule = {
                    month: secondSelected.closest("table").attr("month"),
                    day: secondSelected.attr("day"),
                    week_day: secondSelected.attr("week_day"),
                    schedule: secondSelected.closest("tr").attr("schedule")
                };
                changeSchedule(firstSchedule, secondSchedule);
            }
        } else {
            //Primeira seleção
            $(this).addClass("schedule-selected");
            var week = $(this).attr("week");
            $(this).closest(".tables-timesheet").find("td[week=" + week + "]").not(this).addClass("schedule-available");
        }
    }
});

function changeSchedule(firstSchedule, secondSchedule) {
    $.ajax({
        url: changeSchedulesURL,
        type: "POST",
        data: {
            classroomId: $("select.classroom-id").val(),
            firstSchedule: firstSchedule,
            secondSchedule: secondSchedule,
            replicate: $(".replicate-actions").is(":checked") ? 1 : 0
        }, beforeSend: function () {
            $(".loading-timesheet").css("display", "inline-block");
            $(".table-container").css("opacity", 0.3).css("pointer-events", "none");
            $(".classroom_fk").attr("disabled", "disabled");
            $(".btn-generate-timesheet").attr("disabled", "disabled");
        },
    }).success(function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $.each(data.changes, function () {
                var firstScheduleBlock = $("table[month=" + this.firstSchedule.month + "] tr[schedule=" + this.firstSchedule.schedule + "] td[day=" + this.firstSchedule.day + "]").children();
                var secondScheduleBlock = $("table[month=" + this.secondSchedule.month + "] tr[schedule=" + this.secondSchedule.schedule + "] td[day=" + this.secondSchedule.day + "]").children();
                $("table[month=" + this.firstSchedule.month + "] tr[schedule=" + this.firstSchedule.schedule + "] td[day=" + this.firstSchedule.day + "]").html(secondScheduleBlock);
                $("table[month=" + this.secondSchedule.month + "] tr[schedule=" + this.secondSchedule.schedule + "] td[day=" + this.secondSchedule.day + "]").html(firstScheduleBlock);
            });
        }
        $(".schedule-selected").removeClass("schedule-selected");
        $(".schedule-available").removeClass("schedule-available");

    }).complete(function () {
        $(".loading-timesheet").hide();
        $(".table-container").css("opacity", 1).css("pointer-events", "auto");
        $(".classroom_fk").removeAttr("disabled");
        $(".btn-generate-timesheet").removeAttr("disabled");
    });
}

$(document).on("click", ".schedule-selected .instructor-name", function () {
    var instructorId = $(this).attr("instructor_id");
    var disciplineId = $(this)
        .parent()
        .find(".discipline-name")
        .attr("discipline_id");
    var scheduleId = $(this)
        .parent()
        .attr("schedule");

    $.ajax({
        url: getInstructorsUrl,
        type: "POST",
        data: {
            discipline: disciplineId
        }
    }).success(function (result) {
        $("#change-instructor-schedule").val(scheduleId);
        $("#change-instructor-id").html(result);
        $("#change-instructor-id")
            .val(instructorId)
            .select2();
        $("#change-instructor-modal").modal();
    });
});
$(document).on("click", "#change-instructor-button", function () {
    $.ajax({
        url: changeInstructorUrl,
        type: "POST",
        data: {
            schedule: $("#change-instructor-schedule").val(),
            instructor: $("#change-instructor-id").val()
        }
    }).success(function (result) {
        getTimesheet(result);
        $("#change-instructor-modal").modal("hide");
    });
});

$(document).on("click", "#add-instructors-disciplines-button", function () {
    $("#add-instructors-disciplines-form").submit();
});