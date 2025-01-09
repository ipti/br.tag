$("#classesSearch").on("click", function () {
    if ($("#classrooms").val() !== "" && $("#month").val() !== ""
        && $("#instructor").val() !== "" && $("#disciplines").val() !== "") {
        $(".alert-required-fields, .alert-incomplete-data").hide();
        jQuery.ajax({
            type: "POST",
            url: "?r=instructor/getFrequency",
            cache: false,
            data: {
                instructor: $("#instructor").val(),
                classroom: $("#classrooms").val(),
                discipline: $("#disciplines").val(),
                month: $("#month").val(),
            },
            beforeSend: function () {
                $(".loading-frequency").css("display", "inline-block");
                $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
                $("#classroom, #month, #disciplines, #classesSearch").attr("disabled", "disabled");
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.valid) {
                    var html = "";
                    html += "" +
                        "<table class='t-accordion table-frequency table table-bordered table-striped table-hover'>" +
                        "<thead class='t-accordion__head'>" +
                        "<tr><th class='table-title' colspan='" + (Object.keys(data.instructors[0].schedules).length + 1) + "'>" + ($('#disciplines').select2('data').text) + "</th></tr>";
                    var daynameRow = "";
                    var dayRow = "";
                    var scheduleRow = "";
                    var checkboxRow = "";
                    $.each(data.instructors[0].schedules, function () {
                        dayRow += "<th>" + (pad(this.day, 2) + "/" + pad($("#month").val(), 2)) + "</th>";
                        daynameRow += "<th>" + this.week_day + "</th>";
                        scheduleRow += "<th>" + this.schedule + "º Horário</th>";
                        checkboxRow += "<th class='frequency-checkbox-general frequency-checkbox-container " + (!this.available ? "disabled" : "") + "'><input class='frequency-checkbox' type='checkbox' " + (!this.available ? "disabled" : "") + " classroomId='" + $("#classrooms").val() + "' day='" + this.day + "' month='" + $("#month").val() + "' schedule='" + this.schedule + "></th>";
                    });
                    html += "<tr class='day-row'><th></th>" + dayRow + "</tr><tr class='dayname-row'><th></th>" + daynameRow + "</tr>" + ("<tr class='schedule-row'><th></th>" + scheduleRow + "</tr>");
                    html += "</thead><tbody class='t-accordion__body'>";
                    $.each(data.instructors, function (indexinstructor, instructor) {
                        html += "<tr><td class='instructor-name'>" + instructor.instructorName + "</td>";
                        $.each(instructor.schedules, function (indexSchedule, schedule) {
                            var justificationContainer = "";
                            if (schedule.fault) {
                                if (schedule.justification !== null) {
                                    justificationContainer += "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon' title='" + schedule.justification + "'><i class='fa fa-file-text-o'></i><i class='fa fa-file-text'></i></a>";
                                } else {
                                    justificationContainer += "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'><i class='fa fa-file-o'></i><i class='fa fa-file'></i></a>";
                                }
                            }
                            html += "<td class='frequency-checkbox-instructor frequency-checkbox-container " + (!this.available ? "disabled" : "") + "'><input class='frequency-checkbox' type='checkbox' " + (!schedule.available ? "disabled" : "") + " " + (schedule.fault ? "checked" : "") + " classroomId='" + $("#classrooms").val() + "' instructorId='" + instructor.instructorId + "' day='" + schedule.day + "' month='" + $("#month").val() + "' schedule='" + schedule.idSchedule + "'>" + justificationContainer + "</td>";
                        });
                        html += "</tr>";
                    });
                    html += "</tbody></table>";
                    $("#frequency-container").html(html).show();
                    $(".frequency-checkbox-general").each(function () {
                        var day = $(this).find(".frequency-checkbox").attr("day");
                        $(this).find(".frequency-checkbox").prop("checked", $(".frequency-checkbox-instructor .frequency-checkbox[day=" + day + "]:checked").length === $(".frequency-checkbox-instructor .frequency-checkbox[day=" + day + "]").length);

                    });
                    $('[data-toggle="tooltip"]').tooltip({ container: "body" });
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
    } else {
        $(".alert-required-fields").show();
        $("#frequency-container, .alert-incomplete-data").hide();
    }
});

$(document).on("click", ".frequency-checkbox-container", function (e) {
    if (e.target === this && !$(this).hasClass("disabled")) {
        $(this).find(".frequency-checkbox").prop("checked", !$(this).find(".frequency-checkbox").is(":checked")).trigger("change");
    }
});

$("#instructor").on("change", function () {
    $("#classroom").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=instructor/getFrequencyClassroom",
            cache: false,
            data: {
                instructor: $("#instructor").val(),
            },
            success: function (response) {
                if (response === "") {
                    $("#classrooms").html("<option value='-1'></option>").trigger("change.select2").show();
                } else {
                    $("#classrooms").html(decodeHtml(response)).trigger("change.select2").show();
                }
                $(".classroom-container").show();
            },
        });
    } else {
        $(".classroom-container").hide();
    }
});

$("#classrooms").on("change", function () {
    $("#disciplines").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=instructor/getFrequencyDisciplines",
            cache: false,
            data: {
                instructor: $("#instructor").val(),
                classroom: $("#classrooms").val(),
            },
            success: function (response) {
                if (response === "") {
                    $("#disciplines").html("<option value='-1'></option>").trigger("change.select2").show();
                } else {
                    $("#disciplines").html(decodeHtml(response)).trigger("change.select2").show();
                }
                $(".disciplines-container").show();
            },
        });
    } else {
        $(".disciplines-container").hide();
    }
});

$(document).on("change", ".frequency-checkbox", function () {
    var checkbox = this;
    $.ajax({
        type: "POST",
        url: "?r=instructor/saveFrequency",
        cache: false,
        data: {
            classroomId: $("#classrooms").val(),
            day: $(this).attr("day"),
            month: $(this).attr("month"),
            schedule: $(this).attr("schedule"),
            instructorId: $("#instructor").val(),
            fault: $(this).is(":checked") ? 1 : 0,
        },
        beforeSend: function () {
            $(".loading-frequency").css("display", "inline-block");
            $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
            $("#classroom, #month, #disciplines, #classesSearch").attr("disabled", "disabled");
        },
        complete: function (response) {
            if ($(checkbox).attr("instructorId") === undefined) {
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
    $("#justification-instructorid").val(checkbox.attr("instructorid"));
    $("#justification-day").val(checkbox.attr("day"));
    $("#justification-month").val(checkbox.attr("month"));
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
        url: "?r=instructor/saveJustification",
        cache: false,
        data: {
            classroomId: $("#justification-classroomid").val(),
            instructorId: $("#justification-instructorid").val(),
            day: $("#justification-day").val(),
            month: $("#justification-month").val(),
            schedule: $("#justification-schedule").val(),
            justification: $(".justification-text").val()
        },
        beforeSend: function () {
            $("#save-justification-modal").find(".modal-body").css("opacity", 0.3).css("pointer-events", "none");
            $("#save-justification-modal").find("button").attr("disabled", "disabled");
            $("#save-justification-modal").find(".centered-loading-gif").show();
        },
        success: function (data) {
            var justification = $(".table-frequency tbody .frequency-checkbox[instructorid=" + $("#justification-instructorid").val() + "][day=" + $("#justification-day").val() + "][month=" + $("#justification-month").val() + "]").parent().find(".frequency-justification-icon");
            if ($(".justification-text").val() == "") {
                justification.html("<i class='fa fa-file-o'></i><i class='fa fa-file'></i>");
                justification.attr("data-original-title", "").tooltip('hide');
            } else {
                justification.html("<i class='fa fa-file-text-o'></i><i class='fa fa-file-text'></i>");
                justification.attr("data-original-title", $(".justification-text").val()).tooltip({ container: "body" });
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
