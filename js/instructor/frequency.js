$("#classesSearch").on("click", function () {
    if ($("#instructor").val() !== "" && $("#month").val() !== "") {
        $(".alert-required-fields, .alert-incomplete-data").hide();
        jQuery.ajax({
            type: "POST",
            url: "?r=instructor/getFrequency",
            cache: false,
            data: {
                instructor: $("#instructor").val(),
                month: $("#month").val(),
            },
            beforeSend: function () {
                $(".loading-frequency").css("display", "inline-block");
                $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
                $("#month, #classesSearch").attr("disabled", "disabled");
            },
            success: function (response) {
                let data = JSON.parse(response);
                if (data.valid) {
                    let instructor = data.instructors[0];
                    let html = "<table class='t-accordion table-frequency table table-bordered table-striped table-hover'>" +
                        "<thead class='t-accordion__head'>" +
                        "<tr><th class='table-title' colspan='" + (instructor.schedules.length + 1) + "'>" + instructor.instructorName + "</th></tr>";

                    let dayRow = "";
                    let daynameRow = "";
                    $.each(instructor.schedules, function () {
                        dayRow     += "<th>" + pad(this.day, 2) + "/" + pad($("#month").val(), 2) + "</th>";
                        daynameRow += "<th>" + this.week_day + "</th>";
                    });
                    html += "<tr class='day-row'><th></th>" + dayRow + "</tr>" +
                            "<tr class='dayname-row'><th></th>" + daynameRow + "</tr>" +
                            "</thead><tbody class='t-accordion__body'>";

                    html += "<tr><td class='instructor-name'>" + instructor.instructorName + "</td>";
                    $.each(instructor.schedules, function (i, schedule) {
                        let justificationIcon = "";
                        if (schedule.fault) {
                            if (schedule.justification !== null) {
                                justificationIcon = "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon' title='" + schedule.justification + "'><i class='fa fa-file-text-o'></i><i class='fa fa-file-text'></i></a>";
                            } else {
                                justificationIcon = "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'><i class='fa fa-file-o'></i><i class='fa fa-file'></i></a>";
                            }
                        }
                        html += "<td class='frequency-checkbox-instructor frequency-checkbox-container" + (!schedule.available ? " disabled" : "") + "'>" +
                            "<input class='frequency-checkbox' type='checkbox'" +
                            (!schedule.available ? " disabled" : "") +
                            (schedule.fault ? " checked" : "") +
                            " instructorId='" + instructor.instructorId + "'" +
                            " day='" + schedule.day + "'" +
                            " month='" + $("#month").val() + "'>" +
                            justificationIcon + "</td>";
                    });
                    html += "</tr></tbody></table>";

                    $("#frequency-container").html(html).show();
                    $('[data-toggle="tooltip"]').tooltip({ container: "body" });
                } else {
                    $("#frequency-container").hide();
                    $(".alert-incomplete-data").html(data.error).show();
                }
            },
            complete: function () {
                $(".loading-frequency").hide();
                $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
                $("#month, #classesSearch").removeAttr("disabled");
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

$(document).on("change", ".frequency-checkbox", function () {
    let checkbox = this;
    $.ajax({
        type: "POST",
        url: "?r=instructor/saveFrequency",
        cache: false,
        data: {
            instructorId: $(this).attr("instructorid"),
            day: $(this).attr("day"),
            month: $(this).attr("month"),
            fault: $(this).is(":checked") ? 1 : 0,
        },
        beforeSend: function () {
            $(".loading-frequency").css("display", "inline-block");
            $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
            $("#month, #classesSearch").attr("disabled", "disabled");
        },
        complete: function () {
            if ($(checkbox).is(":checked")) {
                if (!$(checkbox).parent().find(".frequency-justification-icon").length) {
                    $(checkbox).parent().append("<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'><i class='fa fa-file-o'></i><i class='fa fa-file'></i></a>");
                }
            } else {
                $(checkbox).parent().find(".frequency-justification-icon").remove();
            }
            $(".loading-frequency").hide();
            $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
            $("#month, #classesSearch").removeAttr("disabled");
        },
    });
});

$(document).on("click", ".frequency-justification-icon", function () {
    let checkbox = $(this).parent().find(".frequency-checkbox");
    $("#justification-instructorid").val(checkbox.attr("instructorid"));
    $("#justification-day").val(checkbox.attr("day"));
    $("#justification-month").val(checkbox.attr("month"));
    $(".justification-text").val($(this).attr("data-original-title") || "");
    $("#save-justification-modal").modal("show");
});

$("#save-justification-modal").on("shown", function () {
    $(".justification-text").focus();
});

$(document).on("click", ".btn-save-justification", function () {
    $.ajax({
        type: "POST",
        url: "?r=instructor/saveJustification",
        cache: false,
        data: {
            instructorId: $("#justification-instructorid").val(),
            day: $("#justification-day").val(),
            month: $("#justification-month").val(),
            justification: $(".justification-text").val()
        },
        beforeSend: function () {
            $("#save-justification-modal").find(".modal-body").css("opacity", 0.3).css("pointer-events", "none");
            $("#save-justification-modal").find("button").attr("disabled", "disabled");
            $("#save-justification-modal").find(".centered-loading-gif").show();
        },
        success: function () {
            let justification = $(".table-frequency tbody .frequency-checkbox[instructorid=" + $("#justification-instructorid").val() + "][day=" + $("#justification-day").val() + "][month=" + $("#justification-month").val() + "]").parent().find(".frequency-justification-icon");
            if ($(".justification-text").val() === "") {
                justification.html("<i class='fa fa-file-o'></i><i class='fa fa-file'></i>");
                justification.attr("data-original-title", "").tooltip("hide");
            } else {
                justification.html("<i class='fa fa-file-text-o'></i><i class='fa fa-file-text'></i>");
                justification.attr("data-original-title", $(".justification-text").val()).tooltip({ container: "body" });
            }
            $("#save-justification-modal").modal("hide");
        },
        complete: function () {
            $("#save-justification-modal").find(".modal-body").css("opacity", 1).css("pointer-events", "auto");
            $("#save-justification-modal").find("button").removeAttr("disabled");
            $("#save-justification-modal").find(".centered-loading-gif").hide();
        },
    });
});

$(document).on("keyup", ".justification-text", function (e) {
    if (e.keyCode === 13) {
        $(".btn-save-justification").trigger("click");
    }
});
