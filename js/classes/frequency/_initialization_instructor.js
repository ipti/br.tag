function load() {
    if ($("#classroom").val() !== "Selecione a turma" && $("#month").val() !== "" && (!$("#disciplines").is(":visible") || $("#disciplines").val() !== "")) {
        $(".alert-required-fields, .alert-incomplete-data").hide();
        var monthSplit = $("#month").val().split("-");
        var fundamentalMaior = Number(
            $("#classroom option:selected").attr("fundamentalmaior")
        );
        jQuery.ajax({
            type: "POST",
            url: "?r=classes/getFrequency",
            cache: false,
            data: {
                classroom: $("#classroom").val(),
                fundamentalMaior: fundamentalMaior,
                discipline: $("#disciplines").val(),
                month: monthSplit[1],
                year: monthSplit[0]
            },
            beforeSend: function () {
                $(".loading-frequency").css("display", "inline-block");
                $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
                $(".table-frequency-head").css("opacity", 0.3).css("pointer-events", "none");
                $("#classroom, #month, #disciplines, #classesSearch").attr(
                    "disabled",
                    "disabled"
                );
            },

            success: function (response) {
                var data = JSON.parse(response);
                if (data.valid) {
                    var accordion = "";
                    accordion +=
                        '<div id="accordion" class="t-accordeon-secondary">';
                    var item = 0;
                    $.each(data.students[0].schedules, function () {
                        var dia = this.day;
                        var mes = monthSplit[1];
                        var ano = monthSplit[0];
                        fault = this.fault;
                        item++;
                        accordion +=
                            `
            <div  class='t-accordeon-container ui-accordion-header'>
              <table class='table-frequency-head table'>
                <thead>
                  <tr>
                    <th>
                      <div class="" style='display:flex;'>
                        Nome
                      </div>
                    </th>
                    <th class='justify-content--end'  style='display:flex;'>
                        ${this.day}/${pad(mes, 2, 0)}/${ano}
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class='ui-accordion-content'>  
              <table class='table-frequency table'>
                <tbody>`;
                        $.each(data.students, function (indexStudent, student) {
                            var hasFaults = student.schedules.filter((schedule) => dia == schedule.day && mes == monthSplit[1] && ano == monthSplit[0] && schedule.fault == true).length > 0;

                            accordion += `<tr>
                        <td class='student-name'>
                          <div class='t-accordeon-container-table'>
                            ${student.studentName}
                            <a href='javascript:;' studentId=${student.studentId} day=${dia} data-toggle='tooltip' class='frequency-justification-icon ${!hasFaults ? 'hide' : 'show'}' title=''>
                              <span class='t-icon-annotation icon-color'></span>
                            </a>
                          </div>
                            </td>`;
                            $.each(student.schedules, function (indexSchedule, schedule) {
                                if (dia == schedule.day && mes == monthSplit[1] && ano == monthSplit[0]) {
                                    var justificationContainer = "";
                                    if (schedule.fault) {
                                        if (schedule.justification !== null) {
                                            justificationContainer +=
                                                "data-toggle='tooltip' data-placement='left' title='" + schedule.justification + "'";
                                        } else {
                                            justificationContainer +=
                                                ""
                                        }
                                    }

                                    accordion += "" +
                                        "<td class='frequency-checkbox-student justify-content--end frequency-checkbox-container' " + (!this.available ? $("disabled") : "") + ">" +
                                        "<input class='frequency-checkbox' type='checkbox' " +
                                        (!schedule.available ? "disabled " : "") +
                                        (schedule.fault ? "checked " : "") +
                                        "classroomId = '" + $("#classroom").val() + "' " +
                                        "studentId = '" + student.studentId + "' " +
                                        "day = '" + schedule.day + "' " +
                                        "month = '" + mes + "' " +
                                        "year = '" + ano + "' " +
                                        "schedule = '" + schedule.schedule + "' " +
                                        "fundamentalMaior = '" + fundamentalMaior + "' " +
                                        justificationContainer +
                                        "</td>";
                                }


                            });
                            accordion += `</tr>`;
                        });
                        accordion +=
                            `</tbody>
              </table>
            </div>`;
                    });
                    accordion += `</div>`;
                    $("#frequency-container").html(accordion).show();


                    $(function () {
                        $("#accordion").accordion({
                            collapsible: true,
                            icons: null,
                        });
                    });
                    $(".frequency-checkbox-general").each(function () {
                        var day = $(this).find(".frequency-checkbox").attr("day");
                        $(this)
                            .find(".frequency-checkbox")
                            .prop("checked", $(".frequency-checkbox-student .frequency-checkbox[day=" + day + "]:checked").length === $(".frequency-checkbox-student .frequency-checkbox[day=" + day + "]").length);
                    });
                    $('[data-toggle="tooltip"]').tooltip({container: "body"});
                } else {
                    $("#frequency-container").hide();
                    $(".alert-incomplete-data").html(data.error).show();
                }
            },
            complete: function () {
                $(".loading-frequency").hide();
                $(".table-frequency").css("opacity", 1).css("pointer-events", "auto").css("background-color", "white");
                $(".table-frequency-head").css("opacity", 1).css("pointer-events", "auto");
                $("#classroom, #month, #disciplines, #classesSearch").removeAttr(
                    "disabled"
                );
            },
        });
    } else {
        $(".alert-required-fields").show();
        $("#frequency-container, .alert-incomplete-data").hide();
    }
};

function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

$(document).on("click", ".frequency-checkbox-container", function (e) {
    if (e.target === this && !$(this).hasClass("disabled")) {
        $(this)
            .find(".frequency-checkbox")
            .prop("checked", !$(this).find(".frequency-checkbox").is(":checked"))
            .trigger("change");
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
                fundamentalMaior: $("#classroom > option:selected").attr("fundamentalMaior")
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

                    if ($("#classroom > option:selected").attr("fundamentalMaior") === "1") {
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

$(".js-load-frequency").on("change", function () {
    load();
});

$(document).on("change", ".frequency-checkbox", function () {
    var checkbox = this;
    var monthSplit = $("#month").val().split("-");
    $.ajax({
        type: "POST",
        url: "?r=classes/saveFrequency",
        cache: false,
        data: {
            classroomId: $(this).attr("classroomId"),
            day: $(this).attr("day"),
            month: monthSplit[1],
            year: monthSplit[0],
            schedule: $(this).attr("schedule"),
            studentId: $(this).attr("studentId"),
            fault: $(this).is(":checked") ? 1 : 0,
            fundamentalMaior: $(this).attr("fundamentalMaior"),
        },

        beforeSend: function () {
            $(".loading-frequency").css("display", "inline-block");
            $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
            $(".table-frequency-head").css("opacity", 0.3).css("pointer-events", "none");
            $("#classroom, #month, #disciplines, #classesSearch").attr(
                "disabled",
                "disabled"
            );
        },
        complete: function () {
            if ($(checkbox).is(":checked")) {
                $('[studentid=' + $(checkbox).attr('studentid') + '][day=' + $(checkbox).attr('day') + '].frequency-justification-icon').removeClass("hide").addClass("show");
            } else {
                $('[studentid=' + $(checkbox).attr('studentid') + '][day=' + $(checkbox).attr('day') + '].frequency-justification-icon').removeClass("show").addClass("hide");
            }

            $(".loading-frequency").hide();
            $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
            $(".table-frequency-head").css("opacity", 1).css("pointer-events", "auto");
            $("#classroom, #month, #disciplines, #classesSearch").removeAttr(
                "disabled"
            );
        },
    });
});

$(document).on("click", ".frequency-justification-icon", function () {
    var checkbox = $('[studentid=' + $(this).attr('studentid') + '].frequency-checkbox');

    $("#justification-classroomid").val(checkbox.attr("classroomid"));
    $("#justification-studentid").val(checkbox.attr("studentid"));

    $("#justification-day").val(checkbox.attr("day"));
    $("#justification-month").val(checkbox.attr("month"));
    $("#justification-year").val(checkbox.attr("year"));
    $("#justification-schedule").val(checkbox.attr("schedule"));
    $("#justification-fundamentalmaior").val(checkbox.attr("fundamentalmaior"));
    $(".justification-text").val($(this).closest("tr").find(".frequency-checkbox").attr("data-original-title"));
    $("#save-justification-modal").modal("show");
});

$("#save-justification-modal").on("shown", function () {
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
            fundamentalMaior: $("#justification-fundamentalmaior").val(),
            justification: $(".justification-text").val(),
        },
        beforeSend: function () {
            $("#save-justification-modal").find(".modal-body").css("opacity", 0.3).css("pointer-events", "none");
            $("#save-justification-modal").find("button").attr("disabled", "disabled");
            $("#save-justification-modal").find(".centered-loading-gif").show();
        },
        success: function (data) {
            var justification = $(".table-frequency tbody .frequency-checkbox[studentid=" + $("#justification-studentid").val() + "][day=" + $("#justification-day").val() + "][month=" + $("#justification-month").val() + "][year=" + $("#justification-year").val() + "]").parent().parent().find(".frequency-justification-icon");

            if ($(".justification-text").val() == "") {
                justification.attr("data-original-title", "").tooltip("hide");
            } else {
                justification.attr("data-original-title", $(".justification-text").val()).tooltip({container: "body"});
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
    if (e.keyCode == 13) {
        $(".btn-save-justification").trigger("click");
    }
});
