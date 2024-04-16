function generateCheckboxItems(student, dia, mes, ano, fundamentalMaior, monthSplit, date) {
     const index = student.schedules.findIndex(schedule => schedule.date === date);
     const schedule = student.schedules[index];

    let checkboxItem = '';
    if (dia == schedule.day && mes == monthSplit[1] && ano == monthSplit[0]) {
        let justificationContainer = "";
        if (schedule.fault) {
            if (schedule.justification !== null) {
                justificationContainer +=
                    "data-toggle='tooltip' data-placement='left' title='" + schedule.justification + "'";
            }
        }
        checkboxItem = `
            <span class="align-items--center" style='margin-left:5px;'>
                <a href='javascript:;' style='margin-left:5px;' studentId=${student.studentId} day=${dia} data-toggle='tooltip' class='frequency-justification-icon  ${!schedule.fault ? 'hide' : ''}' title=''>
                    <span class='t-icon-annotation icon-color'></span>
                </a>
                <span class="frequency-checkbox-container" ${(!schedule.available ? "disabled" : "")}>
                    <input class='frequency-checkbox' type='checkbox'
                        ${(!schedule.available ? "disabled" : "")}
                        ${(schedule.fault ? "checked" : "")}
                        classroomId='${$("#classroom").val()}'
                        studentId='${student.studentId}'
                        day='${schedule.day}'
                        month='${mes}'
                        year='${ano}'
                        schedule='${schedule.schedule}'
                        fundamentalMaior='${fundamentalMaior}'
                        ${justificationContainer}
                    />
                </span>
            </span>`;
    }
    return checkboxItem;
}
function generateStudentLines(data, dia, mes, ano, fundamentalMaior, monthSplit, date) {
    return data.students.reduce((line, student) => {
        return line + `
            <div class='justify-content--space-between t-padding-small--top t-padding-small--bottom' style="border-bottom:1px #e8e8e8 solid;">
                <div>${student.studentName}</div>
                <div style='display:flex;'>
                    ${generateCheckboxItems(student, dia, mes, ano, fundamentalMaior, monthSplit, date)}
                </div>
            </div>`;
    }, '');
}
function generateScheduleDays(data, monthSplit, fundamentalMaior) {
    return data.scheduleDays.reduce((acc, scheduleDays) => {
        let dia = scheduleDays.day;
        let mes = monthSplit[1];
        let ano = monthSplit[0];
        return acc + `
            <div class="ui-accordion-header justify-content--space-between">
                <div>Aula do dia ${scheduleDays.date}</div>
                <div>
                    <span class="t-icon-down_arrow arrow"></span>
                </div>
            </div>
            <div class='ui-accordion-content'>
                <div style='width: 100%; overflow-x:auto;'>
                    ${generateStudentLines(data, dia, mes, ano, fundamentalMaior, monthSplit, scheduleDays.date)}
                </div>
            </div>`;
    }, '');
}

function load() {
    if ($("#classroom").val() !== "Selecione a turma" && $("#month").val() !== "" && (!$("#disciplines").is(":visible") || $("#disciplines").val() !== "")) {
        $(".alert-required-fields, .alert-incomplete-data").hide();
        let monthSplit = $("#month").val().split("-");
        let fundamentalMaior = Number(
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
                let data = JSON.parse(response);
                console.log(response)
                if (data.valid) {
                    let accordion = $('<div id="accordion" class="t-accordeon-secondary"></div>');

                    accordion.append(generateScheduleDays(data, monthSplit, fundamentalMaior))
                    $("#frequency-container").html(accordion).show();

                    $(function () {
                        $("#accordion").accordion({
                            collapsible: true,
                            icons: null,
                        });
                    });
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
    let checkbox = this
    let monthSplit = $("#month").val().split("-");
        $.ajax({
            type: "POST",
            url: "?r=classes/saveFrequencies",
            cache: false,
            data: {
                classroomId: $(this).attr("classroomId"),
                day: $(this).attr("day"),
                month: monthSplit[1],
                year: monthSplit[0],
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
                $(checkbox).parent().parent().find('.frequency-justification-icon').toggleClass('hide')

                $(".loading-frequency").hide();
                $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
                $(".table-frequency-head").css("opacity", 1).css("pointer-events", "auto");
                $("#classroom, #month, #disciplines, #classesSearch").removeAttr(
                    "disabled"
                );
            },
        })
});

$(document).on("click", ".frequency-justification-icon", function () {
    let checkbox = $(this).parent().find(".frequency-checkbox");
    $("#justification-classroomid").val(checkbox.attr("classroomid"));
    $("#justification-studentid").val(checkbox.attr("studentid"));

    $("#justification-day").val(checkbox.attr("day"));
    $("#justification-month").val(checkbox.attr("month"));
    $("#justification-year").val(checkbox.attr("year"));
    $("#justification-schedule").val(checkbox.attr("schedule"));
    $("#justification-fundamentalmaior").val(checkbox.attr("fundamentalmaior"));
    $(".justification-text").val($(this).parent().find(".frequency-checkbox").attr("title"));
    $("#save-justification-modal").modal("show");
});

$("#save-justification-modal").on("shown", function () {
    $(".justification-text").focus();
});

$(document).on("click", ".btn-save-justification", function () {
    let justification = $(".frequency-checkbox[studentid=" + $("#justification-studentid").val() + "][schedule=" + $("#justification-schedule").val() + "][day=" + $("#justification-day").val() + "][month=" + $("#justification-month").val() + "][year=" + $("#justification-year").val() + "]").parent().parent().find(".frequency-justification-icon");

        $.ajax({
            type: "POST",
            url: "?r=classes/SaveJustifications",
            cache: false,
            data: {
                classroomId: $("#justification-classroomid").val(),
                studentId: $("#justification-studentid").val(),
                day: $("#justification-day").val(),
                month: $("#justification-month").val(),
                year: $("#justification-year").val(),
                fundamentalMaior: $("#justification-fundamentalmaior").val(),
                justification: $(".justification-text").val(),
            },
            beforeSend: function () {
                $("#save-justification-modal").find(".modal-body").css("opacity", 0.3).css("pointer-events", "none");
                $("#save-justification-modal").find("button").attr("disabled", "disabled");
                $("#save-justification-modal").find(".centered-loading-gif").show();
            },
            success: function (data) {

                if ($(".justification-text").val() == "") {
                    justification.attr("title", "").tooltip("hide");
                } else {
                    justification.parent().find(".frequency-checkbox").attr("title", $(".justification-text").val())
                    justification.attr("title", $(".justification-text").val()).tooltip({container: "body"});
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
