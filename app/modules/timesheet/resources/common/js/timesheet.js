$(document).on("change", "#classroom_fk", function () {
    if ($(this).val() !== "") {
        var select = this;
        $(select).attr("disabled", "disabled");
        $.ajax({
            url: getTimesheetURL,
            type: "POST",
            data: {
                cid: $(this).val()
            },
            beforeSend: function () {
                $(".loading-alert").addClass("display-hide");
                $(".loading-timesheet").css("display", "inline-block");
                $(".table-container").css("opacity", 0.3).css("pointer-events", "none");
                $(".btn-generate-timesheet").attr("disabled", "disabled");
            },
        }).success(function (data) {
            data = JSON.parse(data);
            getTimesheet(data);
            var html = "<option></option>";
            $.each(data.disciplines, function () {
                html += "<option value='" + this.disciplineId + "'>" + this.disciplineName + "</option>";
            });
            $(".modal-add-schedule-discipline").html(html);
            $(".modal-add-schedule-discipline").select2('destroy');
            $(".modal-add-schedule-discipline").select2({
                placeholder: "Selecione a Disciplina...",
                width: "100%"
            });
        }).complete(function () {
            $(".loading-timesheet").hide();
            $(".table-container").css("opacity", 1).css("pointer-events", "auto");
            $(select).removeAttr("disabled");
            $(".btn-generate-timesheet").removeAttr("disabled");
        });
    } else {
        $(".schedule-info").addClass("display-hide");
        $(".table-container").hide();
    }
});

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
            $(".btn-generate-timesheet").attr("disabled", "disabled");
            $(".table-container").css("opacity", 0.3).css("pointer-events", "none");
        },
    }).success(function (data) {
        data = JSON.parse(data);
        getTimesheet(data);
    }).complete(function () {
        $(".loading-timesheet").hide();
        $(".btn-generate-timesheet").removeAttr("disabled");
        $(".table-container").css("opacity", 1).css("pointer-events", "auto");
    });
}

function getTimesheet(data) {
    $(".loading-alert").addClass("display-hide");
    $(".schedule-info").removeClass("display-hide");
    $("#turn").hide();
    $(".table-container").show();
    if (data.valid == null) {
        $(".schedule-info").addClass("display-hide");
    } else if (!data.valid) {
        if (data.error == "curricularMatrix" || data.error == "calendar") {
            $(".loading-alert").removeClass("display-hide");
            $(".schedule-info").addClass("display-hide");
            $(".table-container").hide();
        } else {
            $(".tables-timesheet tbody tr td").children().remove();
        }
    } else {
        $(".tables-timesheet tbody").children().remove();
        var turn = "";
        var lastMonthWeek = 1;
        for (var month = 1; month <= 12; month++) {
            var html = "";

            for (var schedule = 1; schedule <= 10; schedule++) {
                var weekDayCount = Number($(".table-month[month=" + month + "]").attr("first-day-weekday"));
                var week = lastMonthWeek;
                html += "<tr schedule='" + schedule + "'><th>" + schedule + "º</th>";

                for (var day = 1; day <= Number($(".table-month[month=" + month + "]").attr("days-count")); day++) {
                    var unavailableDay = data.unavailableDays[month] !== undefined && $.inArray(day.toString(), data.unavailableDays[month]) !== -1;
                    if (data.schedules[month] !== undefined && data.schedules[month][schedule] !== undefined && data.schedules[month][schedule][day] !== undefined) {
                        if (turn === "") {
                            if (data.schedules[month][schedule][day].turn === "0") turn = "Manhã";
                            if (data.schedules[month][schedule][day].turn === "1") turn = "Tarde";
                            if (data.schedules[month][schedule][day].turn === "2") turn = "Noite";
                        }
                        var discipline = changeNameLength(data.schedules[month][schedule][day].disciplineName, 30);
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
                        html += "" +
                            "<td class='" + (unavailableDay ? "schedule-unavailable" : "") + "' day='" + day + "' week='" + week + "' week_day='" + weekDayCount + "'>" +
                            "<div schedule='" + data.schedules[month][schedule][day].id + "' class='schedule-block'>" +
                            "<p class='discipline-name' discipline_id='" + data.schedules[month][schedule][day].disciplineId + "' title='" + data.schedules[month][schedule][day].disciplineName + "'>" + discipline + "</p>" +
                            // "<p class='instructor-name' instructor_id='" + info.instructorInfo.id + "' title='" + info.instructorInfo.name + "'>" +
                            // instructor +
                            // "<i class='fa fa-pencil edit-instructor'></i></p>" +
                            // icons +
                            "</div>" +
                            "</td>";
                    } else {
                        html += "<td class='" + (unavailableDay ? "schedule-unavailable" : "") + "' day='" + day + "' week='" + week + "' week_day='" + weekDayCount + "'></td>";
                    }

                    if (weekDayCount === 6) {
                        weekDayCount = 0;
                        week++;
                    } else {
                        weekDayCount++;
                    }
                    if (day === Number($(".table-month[month=" + month + "]").attr("days-count")) && schedule === 10) {
                        lastMonthWeek = week;
                    }
                }
                html += "</tr>";
            }
            $(".tables-timesheet table[month=" + month + "] tbody").html(html);
        }
        $("#turn").text(turn).show();
        $(".table-container").show();
    }
}

function changeNameLength(name, limit) {
    return name.length > limit ? name.substring(0, limit - 3) + "..." : name;
}

$(document).on("click", ".tables-timesheet td", function () {
    if ($(this).hasClass("schedule-selected")) {
        $(this).removeClass("schedule-selected");
        $(".tables-timesheet").find(".schedule-available").removeClass("schedule-available");
        $(".schedule-remove, .schedule-add").remove();
    } else {
        //Já selecionou algum diferente do primeiro e da mesma semana
        if ($(".tables-timesheet").find(".schedule-selected").length > 0) {
            var firstSelected = $(".tables-timesheet").find(".schedule-selected");
            var secondSelected = $(this);

            if ((!firstSelected.find(".schedule-block").length && !secondSelected.find(".schedule-block").length) || !secondSelected.hasClass("schedule-available")) {
                firstSelected.removeClass("schedule-selected");
                $(".tables-timesheet").find(".schedule-available").removeClass("schedule-available");
                $(".schedule-remove, .schedule-add").remove();
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
                swapSchedule(firstSchedule, secondSchedule);
            }
        } else if (!$(this).hasClass("schedule-unavailable")) {
            //Primeira seleção
            $(this).addClass("schedule-selected");
            $(this).closest(".tables-timesheet").find("td[week=" + $(this).attr("week") + "]:not(.schedule-unavailable)").not(this).addClass("schedule-available");
            if ($(this).find(".schedule-block").length) {
                $(this).append("<i class='schedule-remove fa fa-remove'></i>");
            } else {
                $(this).append("<i class='schedule-add fa fa-plus'></i>");
            }
        }
    }
});

$(document).on("click", ".schedule-remove", function (e) {
    e.stopPropagation();
    var schedule = {
        month: $(this).closest("table").attr("month"),
        day: $(this).closest("td").attr("day"),
        week_day: $(this).closest("td").attr("week_day"),
        schedule: $(this).closest("tr").attr("schedule")
    };
    $.ajax({
        url: removeScheduleURL,
        type: "POST",
        data: {
            classroomId: $("select.classroom-id").val(),
            schedule: schedule,
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
            $.each(data.removes, function () {
                $("table[month=" + this.month + "] tr[schedule=" + this.schedule + "] td[day=" + this.day + "]").children().remove();
            });
        }
        $(".schedule-remove").remove();
        $(".schedule-selected").removeClass("schedule-selected");
        $(".schedule-available").removeClass("schedule-available");

    }).complete(function () {
        $(".loading-timesheet").hide();
        $(".table-container").css("opacity", 1).css("pointer-events", "auto");
        $(".classroom_fk").removeAttr("disabled");
        $(".btn-generate-timesheet").removeAttr("disabled");
    });
});

$(document).on("click", ".schedule-add", function (e) {
    e.stopPropagation();
    $(".add-schedule-alert").addClass("no-show");
    $(".modal-replicate-actions").prop("checked", $(".replicate-actions").is(":checked"));
    $(".add-schedule-month").val($(this).closest("table").attr("month"));
    $(".add-schedule-day").val($(this).closest("td").attr("day"));
    $(".add-schedule-weekday").val($(this).closest("td").attr("week_day"));
    $(".add-schedule-schedule").val($(this).closest("tr").attr("schedule"));
    $(".modal-add-schedule-discipline").val("").trigger("change.select2");
    $("#addSchedule").modal("show");
});

$(document).on("click", ".btn-add-schedule", function () {
    if ($("select.modal-add-schedule-discipline").val() !== "") {
        $(".add-schedule-alert").addClass("no-show");
        $("#addSchedule").modal("hide");
        $.ajax({
            url: addScheduleURL,
            type: "POST",
            data: {
                classroomId: $("select.classroom-id").val(),
                disciplineId: $("select.modal-add-schedule-discipline").val(),
                schedule: {
                    month: $(".add-schedule-month").val(),
                    day: $(".add-schedule-day").val(),
                    week_day: $(".add-schedule-weekday").val(),
                    schedule: $(".add-schedule-schedule").val()
                },
                replicate: $(".modal-replicate-actions").is(":checked") ? 1 : 0
            }, beforeSend: function () {
                $(".loading-timesheet").css("display", "inline-block");
                $(".table-container").css("opacity", 0.3).css("pointer-events", "none");
                $(".classroom_fk").attr("disabled", "disabled");
                $(".btn-generate-timesheet").attr("disabled", "disabled");
            },
        }).success(function (data) {
            data = JSON.parse(data);
            if (data.valid) {
                $.each(data.adds, function () {
                    var discipline = changeNameLength(this.disciplineName, 30);
                    $(".table-month[month=" + this.month + "] tbody").find("tr[schedule=" + this.schedule + "]").find("td[day=" + this.day + "]").html("" +
                        "<div schedule='" + this.id + "' class='schedule-block'>" +
                        "<p class='discipline-name' discipline_id='" + this.disciplineId + "' title='" + this.disciplineName + "'>" + discipline + "</p>" +
                        "</div>"
                    );
                });
            }
            $(".schedule-add").remove();
            $(".schedule-selected").removeClass("schedule-selected");
            $(".schedule-available").removeClass("schedule-available");
        }).complete(function () {
            $(".loading-timesheet").hide();
            $(".table-container").css("opacity", 1).css("pointer-events", "auto");
            $(".classroom_fk").removeAttr("disabled");
            $(".btn-generate-timesheet").removeAttr("disabled");
        });
    } else {
        $(".add-schedule-alert").removeClass("no-show");
    }
});

function swapSchedule(firstSchedule, secondSchedule) {
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
            $("td.schedule-unavailable").children().remove();
        }
        $(".schedule-remove, .schedule-add").remove();
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
    }).success(function (data) {
        data = JSON.parse(data);
        getTimesheet(data);
        $("#change-instructor-modal").modal("hide");
    });
});

$(document).on("click", "#add-instructors-disciplines-button", function () {
    $("#add-instructors-disciplines-form").submit();
});