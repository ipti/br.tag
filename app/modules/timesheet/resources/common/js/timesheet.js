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
            getTimesheet(data);
            data = JSON.parse(data);
            if (data.disciplines !== undefined) {
                var html = "<option>Selecione uma disciplina</option>";
                $.each(data.disciplines, function () {
                    html += "<option value='" + this.disciplineId + "'>" + this.disciplineName + "</option>";
                });
                $(".modal-add-schedule-discipline").html(html);
            }
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
        getTimesheet(data);
    }).complete(function () {
        $(".loading-timesheet").hide();
        $(".btn-generate-timesheet").removeAttr("disabled");
        $(".table-container").css("opacity", 1).css("pointer-events", "auto");
    });
}

function getTimesheet(data) {
    data = DOMPurify.sanitize(data);
    data = JSON.parse(data);

    $(".loading-alert").addClass("display-hide");
    $(".schedule-info").removeClass("display-hide");
    $("#turn").hide();
    $(".table-container").show();
    if (data.valid == null) {
        $(".schedule-info").addClass("display-hide");
    } else if (!data.valid) {
        if (data.error === "curricularMatrix") {
            $(".loading-alert").removeClass("display-hide").html("A Turma está sem matriz curricular.");
            $(".schedule-info").addClass("display-hide");
            $(".table-container").hide();
        } else if (data.error === "calendar") {
            $(".loading-alert").removeClass("display-hide").html("A Turma está sem Calendário Escolar vinculado.");
            $(".schedule-info").addClass("display-hide");
            $(".table-container").hide();
        } else if (data.error === "frequencyOrClassContentFilled") {
            $(".loading-alert").removeClass("display-hide").html("Permissão negada: a turma já está com frequencia ou aula ministrada preenchida.");
            $("#turn").show();
        }
    } else {
        buildTimesheetStructure(data);
        $(".tables-timesheet tbody").children().remove();
        $(".calendar-icons th").children().remove();
        var lastMonthWeek = 1;
        $.each(data.calendarEvents, function () {
            $(".calendar-icons th[icon-year=" + this.year + "][icon-month=" + this.month + "][icon-day=" + this.day + "]").append(
                '<div class="calendar-timesheet-icon calendar-' + this.color + '">' +
                '<i data-toggle="tooltip" data-placement="bottom" data-original-title="' + this.name + '" class="fa ' + this.icon + '"></i>' +
                '</div>'
            );
        });
        $.each(data.daysPerMonth, function (year, yearResult) {
            $.each(yearResult, function (month) {
                var html = "";

                for (var schedule = 1; schedule <= 10; schedule++) {
                    var weekDayCount = Number($(".table-month[year=" + year + "][month=" + month + "]").attr("first-day-weekday"));
                    var week = lastMonthWeek;
                    html += "<tr schedule='" + schedule + "'><th>" + schedule + "º</th>";

                    for (var day = 1; day <= Number($(".table-month[year=" + year + "][month=" + month + "]").attr("days-count")); day++) {
                        var hardUnavailableDay = data.hardUnavailableDays[year] !== undefined && data.hardUnavailableDays[year][month] !== undefined && $.inArray(day.toString(), data.hardUnavailableDays[year][month]) !== -1;
                        var softUnavailableDay = data.softUnavailableDays[year] !== undefined && data.softUnavailableDays[year][month] !== undefined && $.inArray(day.toString(), data.softUnavailableDays[year][month]) !== -1;

                        var tdClass = "";
                        if (hardUnavailableDay) {
                            tdClass += "hard-unavailable";
                        } else if (softUnavailableDay) {
                            tdClass += "soft-unavailable";
                        }

                        if (data.schedules[year] !== undefined && data.schedules[year][month] !== undefined && data.schedules[year][month][schedule] !== undefined && data.schedules[year][month][schedule][day] !== undefined) {
                            var discipline = changeNameLength(data.schedules[year][month][schedule][day].disciplineName, 30);

                            html += "" +
                                "<td class='" + tdClass + "' day='" + day + "' week='" + week + "' week_day='" + weekDayCount + "'>" +
                                "<div schedule='" + data.schedules[year][month][schedule][day].id + "' discipline_id='" + data.schedules[year][month][schedule][day].disciplineId + "'class='schedule-block'>" +
                                "<p class='discipline-name' title='" + data.schedules[year][month][schedule][day].disciplineName + "'>" + discipline + "</p>" +
                                (hardUnavailableDay || softUnavailableDay ? "<div class='availability-schedule' data-toggle='tooltip' data-placement='top' data-original-title='Alterar Estado da Aula '><i class='fa " + (Number(data.schedules[year][month][schedule][day].unavailable) ? "fa-user-times" : "fa-user-plus") + "'></i></div>" : "") +
                                "</div>" +
                                "</td>";
                        } else {
                            html += "<td class='" + tdClass + "' day='" + day + "' week='" + week + "' week_day='" + weekDayCount + "'></td>";
                        }

                        if (weekDayCount === 6) {
                            weekDayCount = 0;
                            week++;
                        } else {
                            weekDayCount++;
                        }
                        if (day === Number($(".table-month[year=" + year + "][month=" + month + "]").attr("days-count")) && schedule === 10) {
                            lastMonthWeek = week;
                        }
                    }
                    html += "</tr>";
                }
                $(".tables-timesheet table[year=" + year + "][month=" + month + "] tbody").html(html);
                $('[data-toggle="tooltip"]').tooltip({container: "body"});
            });
        });
        calculateWorkload(data.disciplines, false);

        let turn = "";
        switch (data.turn) {
            case "M":
                turn = "Manhã";
                break;
            case "T":
                turn = "Tarde";
                break;
            case "N":
                turn = "Noite";
                break;
            case "I":
                turn = "Integral";
                break;
            default:
                turn = "";

        }
        $("#turn").text(turn).show();

        $(".table-container").show();
    }
}

function buildTimesheetStructure(data) {
    var dayNameFirstLetter = ["D", "S", "T", "Q", "Q", "S", "S"];
    var timesheetStructureHtml = "";
    $.each(data.daysPerMonth, function (year, yearResult) {
        $.each(yearResult, function (month, monthResult) {
            timesheetStructureHtml += '<div class="table-responsive">';
            timesheetStructureHtml += '<table year="' + year + '" month="' + month + '" days-count="' + monthResult.daysCount + '" first-day-weekday="' + monthResult.weekDayOfTheFirstDay + '" class="table-timesheet table-month table table-bordered table-striped table-hover">';
            timesheetStructureHtml += '<thead>';
            timesheetStructureHtml += '<tr>';
            timesheetStructureHtml += '<th class="table-title" colspan="' + (Number(monthResult.daysCount) + 1) + '">' + translateMonthName(monthResult.monthName) + '/' + year + '</th>';
            timesheetStructureHtml += '</tr>';
            timesheetStructureHtml += '<tr class="calendar-icons">';
            timesheetStructureHtml += '<th></th>';
            for (var day = 1; day <= monthResult.daysCount; day++) {
                timesheetStructureHtml += '<th icon-year="' + year + '" icon-month="' + month + '" icon-day="' + day + '"></th>';
            }
            timesheetStructureHtml += '</tr>';
            timesheetStructureHtml += '<tr class="dayname-row">';
            timesheetStructureHtml += '<th></th>';
            var weekDayCount = monthResult.weekDayOfTheFirstDay;
            for (var day = 1; day <= monthResult.daysCount; day++) {
                timesheetStructureHtml += '<th>' + dayNameFirstLetter[weekDayCount] + '</th>';
                weekDayCount = weekDayCount == 6 ? 0 : ++weekDayCount;
            }
            timesheetStructureHtml += '</tr>';
            timesheetStructureHtml += '<tr class="day-row">';
            timesheetStructureHtml += '<th class="schedule">Horário</th>';
            for (var day = 1; day <= monthResult.daysCount; day++) {
                timesheetStructureHtml += '<th>' + day + '</th>';
            }
            timesheetStructureHtml += '</tr>';
            timesheetStructureHtml += '</thead>';
            timesheetStructureHtml += '<tbody></tbody>';
            timesheetStructureHtml += '</table>';
            timesheetStructureHtml += '</div>';
        });
    });
    $(".tables-timesheet").html(timesheetStructureHtml);
}

function calculateWorkload(disciplines, increment) {
    var hasOverflow = false;
    if (!increment) {
        disciplines = sortResults(disciplines, "disciplineName", true);
        var html = "";
        $.each(disciplines, function () {
            var workloadUsed = Number(this.workloadUsed);
            var workloadTotal = Number(this.workloadTotal);
            if (!hasOverflow) {
                hasOverflow = workloadUsed > workloadTotal;
            }
            var workloadColor = workloadUsed > workloadTotal ? "workload-red" : (workloadUsed === workloadTotal ? "workload-green" : "");
            html += "<div class='workload " + workloadColor + "' discipline-id='" + this.disciplineId + "'><div class='workload-discipline'>" + this.disciplineName + "</div><div class='workload-numbers'><span class='workload-used'>" + workloadUsed.toFixed(2).replace(/\.00$/, '') + "</span>/<span class='workload-total'>" + workloadTotal + "</span></div><div class='workload-instructor'>" + (this.instructorName === null ? "SEM PROFESSOR" : this.instructorName) + "</div></div>";
        });
        $(".workloads").find(".workload").remove();
        $(".workloads").append(html);
    } else {
        $.each(disciplines, function () {
            var workload = $(".workload[discipline-id=" + this.disciplineId + "]");
            var workloadUsed = Number(workload.find(".workload-used").text()) + Number(this.workloadUsed);
            var workloadTotal = Number(workload.find(".workload-total").text());
            workloadUsed > workloadTotal
                ? workload.addClass("workload-red").removeClass("workload-green")
                : (workloadUsed === workloadTotal ? workload.addClass("workload-green").removeClass("workload-red") : workload.removeClass("workload-red").removeClass("workload-green"));
            workload.find(".workload-used").text(workloadUsed.toFixed(2).replace(/\.00$/, ''));
        });
        hasOverflow = $(".workloads").find(".workload.workload-red").length;
    }
    if (hasOverflow) {
        $(".workloads-overflow").show();
    } else {
        $(".workloads-overflow").hide();
    }
}

function sortResults(array, prop, asc) {
    array.sort(function (a, b) {
        if (asc) {
            return (a[prop] > b[prop]) ? 1 : ((a[prop] < b[prop]) ? -1 : 0);
        } else {
            return (b[prop] > a[prop]) ? 1 : ((b[prop] < a[prop]) ? -1 : 0);
        }
    });
    return array;
}

function changeNameLength(name, limit) {
    return name.length > limit ? name.substring(0, limit - 3) + "..." : name;
}

$(document).on("click", ".tables-timesheet td", function () {
    if ($(this).hasClass("schedule-selected")) {
        $(this).removeClass("schedule-selected");
        $(".tables-timesheet").find(".schedule-highlighted").removeClass("schedule-highlighted");
        $(".schedule-remove, .schedule-add").remove();
    } else {
        //Já selecionou algum diferente do primeiro e da mesma semana
        if ($(".tables-timesheet").find(".schedule-selected").length > 0) {
            var firstSelected = $(".tables-timesheet").find(".schedule-selected");
            var secondSelected = $(this);

            if ((!firstSelected.find(".schedule-block").length && !secondSelected.find(".schedule-block").length)
                || !secondSelected.hasClass("schedule-highlighted")
                || firstSelected.hasClass("hard-unavailable") || secondSelected.hasClass("hard-unavailable")) {
                firstSelected.removeClass("schedule-selected");
                $(".tables-timesheet").find(".schedule-highlighted").removeClass("schedule-highlighted");
                $(".schedule-remove, .schedule-add").remove();
            } else {
                var firstSchedule = {
                    year: firstSelected.closest("table").attr("year"),
                    month: firstSelected.closest("table").attr("month"),
                    day: firstSelected.attr("day"),
                    week_day: firstSelected.attr("week_day"),
                    schedule: firstSelected.closest("tr").attr("schedule")
                };
                var secondSchedule = {
                    year: secondSelected.closest("table").attr("year"),
                    month: secondSelected.closest("table").attr("month"),
                    day: secondSelected.attr("day"),
                    week_day: secondSelected.attr("week_day"),
                    schedule: secondSelected.closest("tr").attr("schedule")
                };
                swapSchedule(firstSchedule, secondSchedule);
            }
        } else {
            //Primeira seleção
            $(this).addClass("schedule-selected");
            $(this).closest(".tables-timesheet").find("td[week=" + $(this).attr("week") + "]").not(this).addClass("schedule-highlighted");
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
        year: $(this).closest("table").attr("year"),
        month: $(this).closest("table").attr("month"),
        day: $(this).closest("td").attr("day"),
        week_day: $(this).closest("td").attr("week_day"),
        schedule: $(this).closest("tr").attr("schedule"),
        hardUnavailableDaySelected: $(this).closest("td").hasClass("hard-unavailable") ? 1 : 0
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
                $("table[year=" + this.year + "][month=" + this.month + "] tr[schedule=" + this.schedule + "] td[day=" + this.day + "]").children().remove();
            });
            calculateWorkload(data.disciplines, true);
        }
        $(".schedule-remove").remove();
        $(".schedule-selected").removeClass("schedule-selected");
        $(".schedule-highlighted").removeClass("schedule-highlighted");
    }).complete(function () {
        $(".loading-timesheet").hide();
        $(".table-container").css("opacity", 1).css("pointer-events", "auto");
        $(".classroom_fk").removeAttr("disabled");
        $(".btn-generate-timesheet").removeAttr("disabled");
    });
});

$(document).on("click", ".schedule-add", function (e) {
    e.stopPropagation();
    $(this).closest(".hard-unavailable").length
        ? $(".modal-replicate-actions-container").hide()
        : $(".modal-replicate-actions-container").show();
    $(".add-schedule-alert").addClass("no-show");
    $(".modal-replicate-actions").prop("checked", $(".replicate-actions").is(":checked"));
    $(".add-schedule-year").val($(this).closest("table").attr("year"));
    $(".add-schedule-month").val($(this).closest("table").attr("month"));
    $(".add-schedule-day").val($(this).closest("td").attr("day"));
    $(".add-schedule-weekday").val($(this).closest("td").attr("week_day"));
    $(".add-schedule-schedule").val($(this).closest("tr").attr("schedule"));
    $(".add-schedule-hard-unavailable-day").val($(this).closest(".hard-unavailable").length ? 1 : 0);
    $(".modal-add-schedule-discipline").val("").trigger("change.select2");
    $("#addSchedule").modal("show");
});

$(document).on("click", ".replicate-actions-container", function (e) {
    if (e.target === this) {
        $(this).find(".replicate-actions-checkbox").prop("checked", !$(this).find(".replicate-actions-checkbox").is(":checked")).trigger("change");
    }
});

$(document).on("change", ".replicate-actions-checkbox", function (e) {
    $(".replicate-actions-checkbox").prop("checked", $(this).is(":checked"));
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
                    year: $(".add-schedule-year").val(),
                    month: $(".add-schedule-month").val(),
                    day: $(".add-schedule-day").val(),
                    week_day: $(".add-schedule-weekday").val(),
                    schedule: $(".add-schedule-schedule").val()
                },
                replicate: $(".modal-replicate-actions").is(":checked") ? 1 : 0,
                hardUnavailableDaySelected: $(".add-schedule-hard-unavailable-day").val()
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
                    $(".table-month[year=" + this.year + "][month=" + this.month + "] tbody").find("tr[schedule=" + this.schedule + "]").find("td[day=" + this.day + "]").html("" +
                        "<div schedule='" + this.id + "' discipline_id='" + this.disciplineId + "' class='schedule-block'>" +
                        "<p class='discipline-name' title='" + this.disciplineName + "'>" + discipline + "</p>" +
                        ($(".add-schedule-hard-unavailable-day").val() === "1" || this.unavailable ? "<div class='availability-schedule' data-toggle='tooltip' data-placement='top' data-original-title='Alterar Estado da Aula '><i class='fa " + (this.unavailable ? "fa-user-times" : "fa-user-plus") + "'></i></div>" : "") +
                        "</div>"
                    );
                });
                calculateWorkload(data.disciplines, true);
            }
            $(".schedule-add").remove();
            $(".schedule-selected").removeClass("schedule-selected");
            $(".schedule-highlighted").removeClass("schedule-highlighted");
            $('.soft-unavailable .availability-schedule').tooltip({container: "body"}).tooltip({container: "body"});
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

$(document).on('click', ".cancel-add-schedule", function () {
    $(".schedule-add").remove();
    $(".schedule-selected").removeClass("schedule-selected");
    $(".schedule-highlighted").removeClass("schedule-highlighted");
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
                var firstScheduleBlock = $("table[year=" + this.firstSchedule.year + "][month=" + this.firstSchedule.month + "] tr[schedule=" + this.firstSchedule.schedule + "] td[day=" + this.firstSchedule.day + "]").children();
                var secondScheduleBlock = $("table[year=" + this.secondSchedule.year + "][month=" + this.secondSchedule.month + "] tr[schedule=" + this.secondSchedule.schedule + "] td[day=" + this.secondSchedule.day + "]").children();
                $("table[year=" + this.firstSchedule.year + "][month=" + this.firstSchedule.month + "] tr[schedule=" + this.firstSchedule.schedule + "] td[day=" + this.firstSchedule.day + "]").html(secondScheduleBlock);
                $("table[year=" + this.secondSchedule.year + "][month=" + this.secondSchedule.month + "] tr[schedule=" + this.secondSchedule.schedule + "] td[day=" + this.secondSchedule.day + "]").html(firstScheduleBlock);

                var firstScheduleTd = $("table[year=" + this.firstSchedule.year + "][month=" + this.firstSchedule.month + "] tr[schedule=" + this.firstSchedule.schedule + "] td[day=" + this.firstSchedule.day + "]");
                if (!firstScheduleTd.hasClass("hard-unavailable")) {
                    firstScheduleTd.find(".availability-schedule").remove();
                    if (firstScheduleTd.hasClass("soft-unavailable")) {
                        firstScheduleTd.find(".schedule-block").append("<div class='availability-schedule' data-toggle='tooltip' data-placement='top' data-original-title='Alterar Estado da Aula '><i class='fa fa-user-times'></i></div>");
                    }
                } else {
                    firstScheduleTd.children().remove();
                }

                var secondScheduleTd = $("table[year=" + this.secondSchedule.year + "][month=" + this.secondSchedule.month + "] tr[schedule=" + this.secondSchedule.schedule + "] td[day=" + this.secondSchedule.day + "]");
                if (!secondScheduleTd.hasClass("hard-unavailable")) {
                    secondScheduleTd.find(".availability-schedule").remove();
                    if (secondScheduleTd.hasClass("soft-unavailable")) {
                        secondScheduleTd.find(".schedule-block").append("<div class='availability-schedule' data-toggle='tooltip' data-placement='top' data-original-title='Alterar Estado da Aula '><i class='fa fa-user-times'></i></div>");
                    }
                } else {
                    secondScheduleTd.children().remove();
                }
            });
            calculateWorkload(data.disciplines, false);
            $('.soft-unavailable .availability-schedule').tooltip({container: "body"});
        } else {
            $(".last-frequency-day").text(data.lastClassFaultDate === "" ? "Nenhum preenchimento" : data.lastClassFaultDate);
            $(".last-classcontent-day").text(data.lastClassContentDate === "" ? "Nenhum preenchimento" : data.lastClassContentDate);
            $("#unallowedSchedulesSwap").modal("show");
        }
        $(".schedule-remove, .schedule-add").remove();
        $(".schedule-selected").removeClass("schedule-selected");
        $(".schedule-highlighted").removeClass("schedule-highlighted");
    }).complete(function () {
        $(".loading-timesheet").hide();
        $(".table-container").css("opacity", 1).css("pointer-events", "auto");
        $(".classroom_fk").removeAttr("disabled");
        $(".btn-generate-timesheet").removeAttr("disabled");
    });
}

$(document).on("click", ".workloads-activator", function () {
    if ($(".workloads-activator").hasClass("fa-chevron-right")) {
        $(".workloads-activator").addClass("fa-chevron-left").removeClass("fa-chevron-right");
        $(".workloads").show();
    } else {
        $(".workloads-activator").addClass("fa-chevron-right").removeClass("fa-chevron-left");
        $(".workloads").hide();
    }
});

$(document).on("click", ".availability-schedule", function (e) {
    e.stopPropagation();
    var icon = this;
    if (!$(icon).closest(".hard-unavailable").length) {
        var scheduleId = $(icon).closest(".schedule-block").attr("schedule");
        $.ajax({
            url: changeUnavailableScheduleURL,
            type: "POST",
            data: {
                schedule: scheduleId,
            },
            beforeSend: function () {
                $(icon).find("i").addClass("fa-spin").addClass("fa-spinner").removeClass("fa-user-plus").removeClass("fa-user-times");
            },
        }).success(function (data) {
            data = JSON.parse(data);
            $(icon).find("i").removeClass("fa-spin").removeClass("fa-spinner");
            if (data.unavailable) {
                $(icon).find("i").addClass("fa-user-times");
            } else {
                $(icon).find("i").addClass("fa-user-plus");
            }
            calculateWorkload(data.disciplines, true);
        });
    }
});

function translateMonthName(monthName) {
    switch (monthName) {
        case "January":
            return "Janeiro";
        case "February":
            return "Fevereiro";
        case "March":
            return "Março";
        case "April":
            return "Abril";
        case "May":
            return "Maio";
        case "June":
            return "Junho";
        case "July":
            return "Julho";
        case "August":
            return "Agosto";
        case "September":
            return "Setembro";
        case "October":
            return "Outubro";
        case "November":
            return "Novembro";
        case "December":
            return "Dezembro";
    }
}

function pad(num, size) {
    num = num.toString();
    while (num.length < size) num = "0" + num;
    return num;
}