var students_array = new Array();
var index_count = 0;
var classdays = "";
var weekly_schedule = new Array();
var special_days = new Array();
var saturdays = new Array();

function checkSpecialDay(date, specialdays) {
    var count = 0;
    $(specialdays).each(function (i, period) {
        var start_date = new Date(period["start_date"]);
        var end_date = new Date(period["end_date"]);

        if (
            start_date.getTime() <= date.getTime() &&
            end_date.getTime() >= date.getTime()
        ) {
            count++;
        }
    });
    return count > 0;
}

function checkSaturdaySchool(date, saturdays) {
    var count = 0;
    $(saturdays).each(function (i, period) {
        var start_date = new Date(period["start_date"]);
        var end_date = new Date(period["end_date"]);
        if (
            start_date.getTime() <= date.getTime() &&
            end_date.getTime() >= date.getTime()
        ) {
            count++;
        }
    });
    return count > 0;
}

function checkNoSchool(day, no_school_days) {
    var count = 0;
    $(no_school_days).each(function (i, days) {
        var no_school_day = days["day"];
        if (day == no_school_day) {
            count++;
        }
    });
    return count > 0;
}

function checkFault(day, faults) {
    var count = 0;
    $(faults).each(function (i, days) {
        var fault = days["day"];
        if (day == fault) {
            count++;
        }
    });
    return count > 0;
}

function adicionaProfessorPrimario(special_days, no_school, saturdays) {
    var thead = "";
    var month = $("#month").val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();
    thead += "<th><span'>Professor</span></th>";
    for (var day = 1; day <= maxDays; day++) {
        var date = new Date(month + " " + day + " " + year);

        var weekDay = date.getDay();
        if (weekDay != 0) {
            var disabled = "";
            var checked = "";
            var className = 'class = "checkcolor"';
            var value = 1;

            if (checkSpecialDay(date, special_days)) {
                disabled = ' readonly onclick="return false" ';
                checked = " checked ";
                className = 'style = "color:red"';
                value = 2;
            }
            if (checkNoSchool(day, no_school)) {
                checked = " checked ";
            }
            if (weekDay <= 6) {
                var dayForClass = day;
                if (parseInt(dayForClass) < 10) {
                    dayForClass = "0" + dayForClass;
                }
                thead += "<th><span " + className + ">";
                thead +=
                    '<input type="hidden" name=instructor_days[' +
                    day +
                    '] value="' +
                    day +
                    '">';
                thead += day;
                thead +=
                    '<input id="instructor_faults[' +
                    dayForClass +
                    ']" name="instructor_faults[' +
                    day +
                    ']" class="instructor-fault checkbox no-show" type="checkbox" value="' +
                    value +
                    '" style="opacity: 100;"' +
                    checked +
                    disabled +
                    ">";
                thead += "</span></th>";
            }
        }
    }
    return thead;
}

function adicionaProfessorSecundario(
    schedule,
    special_days,
    no_school,
    saturdays
) {
    var thead = "";
    var month = $("#month").val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();
    thead += "<th><span>Professor</span></th>";
    for (var day = 1; day <= maxDays; day++) {
        var date = new Date(month + " " + day + " " + year);

        var weekDay = date.getDay();
        if (isset(schedule[weekDay])) {
            var disabled = "";
            var checked = "";
            var value = 1;

            if (checkSpecialDay(date, special_days)) {
                disabled = ' readonly onclick="return false"';
                checked = " checked ";
                value = 2;
            }
            if (checkNoSchool(day, no_school)) {
                checked = " checked ";
            }
            if (weekDay <= 6) {
                var dayForClass = day;
                if (parseInt(dayForClass) < 10) {
                    dayForClass = "0" + dayForClass;
                }
                thead += '<th><span class="checkcolor">';
                thead +=
                    '<input type="hidden" name=instructor_days[' +
                    day +
                    '] value="' +
                    day +
                    '">';
                thead += day;
                thead +=
                    '<input id="instructor_faults[' +
                    dayForClass +
                    ']" name="instructor_faults[' +
                    day +
                    ']" class="instructor-fault checkbox no-show" type="checkbox" value="' +
                    value +
                    '" style="opacity: 100;"' +
                    checked +
                    disabled +
                    ">";
                thead += "</span></th>";
            }
        }
    }
    return thead;
}

function adicionaEstudantePrimario(
    students,
    special_days,
    no_school,
    saturdays
) {
    var month = $("#month").val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();
    var tbody = "";
    for (var index = 0; index < students.length; index++) {
        var name = students[index]["name"];

        var faults = students[index]["faults"];
        var id = students[index]["id"];
        tbody += '<tr id="' + index + '" style="display: none">';
        tbody += "<td><span>" + name + "</span></td>";

        for (var day = 1; day <= maxDays; day++) {
            var date = new Date(month + " " + day + " " + year);

            var weekDay = date.getDay();

            if (weekDay != 0) {
                var disabled = "";
                var checked = "";
                var value = 1;
                if (checkSpecialDay(date, special_days)) {
                    checked = " checked ";
                    disabled = ' readonly onclick="return false"';
                    value = 2;
                }
                if (checkNoSchool(day, no_school)) {
                    checked = " checked ";
                    disabled = ' readonly onclick="return false"';
                }
                if (checkFault(day, faults)) {
                    checked = " checked ";
                }

                if (weekDay <= 6) {
                    var dayForClass = day;
                    if (parseInt(dayForClass) < 10) {
                        dayForClass = "0" + dayForClass;
                    }
                    tbody += '<td><span class="checkcolor">';
                    tbody += day;
                    tbody +=
                        '<input id="student_faults[' +
                        id +
                        "][" +
                        day +
                        ']" name="student_faults[' +
                        id +
                        "][" +
                        day +
                        ']" class="instructor-fault no-show checkbox student_check' +
                        dayForClass +
                        '" type="checkbox" value="' +
                        value +
                        '" style="opacity: 100;"' +
                        checked +
                        disabled +
                        ">";
                    tbody += "</span></td>";
                }
            }
        }
        tbody += "</tr>";
    }

    return tbody;
}

function adicionaEstudanteSecundario(
    schedule,
    students,
    special_days,
    no_school,
    saturdays
) {
    var month = $("#month").val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();

    var tbody = "";
    for (var index = 0; index < students.length; index++) {
        var name = students[index]["name"];

        var faults = students[index]["faults"];
        var id = students[index]["id"];
        tbody += '<tr id="' + index + '" style="display: none">';
        tbody += "<td><span>" + name + "</span></td>";

        for (var day = 1; day <= maxDays; day++) {
            var date = new Date(month + " " + day + " " + year);

            var weekDay = date.getDay();

            if (isset(schedule[weekDay])) {
                var disabled = "";
                var checked = "";
                var value = 1;
                if (checkSpecialDay(date, special_days)) {
                    checked = " checked ";
                    disabled = 'readonly onclick="return false"';
                    value = 2;
                }
                if (checkNoSchool(day, no_school)) {
                    checked = " checked ";
                    disabled = 'readonly onclick="return false"';
                }
                if (checkFault(day, faults)) {
                    checked = " checked ";
                }

                if (weekDay <= 6) {
                    var dayForClass = day;
                    if (parseInt(dayForClass) < 10) {
                        dayForClass = "0" + dayForClass;
                    }
                    tbody += '<td><span class="checkcolor">';
                    tbody += day;
                    tbody +=
                        '<input id="student_faults[' +
                        id +
                        "][" +
                        day +
                        ']" name="student_faults[' +
                        id +
                        "][" +
                        day +
                        ']" class="instructor-fault checkbox  no-show student_check' +
                        dayForClass +
                        '" " type="checkbox" value="+value+" style="opacity: 100;"' +
                        checked +
                        disabled +
                        ">";
                    tbody += "</span></td>";
                }
            }
        }
        tbody += "</tr>";
    }

    return tbody;
}

function adicionaHorarios(
    schedule,
    special_days,
    no_school,
    saturdays,
    students,
    is_first_to_third_year
) {
    if (is_first_to_third_year == "1") {
        var thead = adicionaProfessorPrimario(special_days, no_school);
        var tbody = adicionaEstudantePrimario(
            students,
            special_days,
            no_school,
            saturdays
        );

        $("#frequency > thead > tr").append(thead);
        $("#frequency > tbody").append(tbody);

        $("#0").show();

        var buttons_div = "<div id='buttons-frequency'></div>";
        $(buttons_div).insertAfter("#widget-frequency");
        if (index_count < students.length) {
            var next_student_button =
                "<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>";
            $("#buttons-frequency").append(next_student_button);
        }
        $("#next-student-button").click(function () {
            addStudentForward(
                schedule,
                special_days,
                no_school,
                saturdays,
                students,
                is_first_to_third_year
            );
        });
    } else {
        var thead = adicionaProfessorSecundario(schedule, special_days, no_school);
        var tbody = adicionaEstudanteSecundario(
            schedule,
            students,
            special_days,
            no_school,
            saturdays
        );

        $("#frequency > thead > tr").append(thead);
        $("#frequency > tbody").append(tbody);

        $("#0").show();

        var buttons_div = "<div id='buttons-frequency'></div>";
        $(buttons_div).insertAfter("#widget-frequency");
        if (index_count < students.length) {
            var next_student_button =
                "<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>";
            $("#buttons-frequency").append(next_student_button);
        }
        $("#next-student-button").click(function () {
            addStudentForward(
                schedule,
                special_days,
                no_school,
                saturdays,
                students,
                is_first_to_third_year
            );
        });
    }
    $(".checkcolor").each(function () {
        if ($(this).find(".checkbox").prop("checked")) {
            $(this).css("color", "red");
        } else {
            $(this).css("color", "black");
        }
    });

    $(".checkcolor").click(function () {
        var checked = $(this).find(".checkbox")[0];
        if ($(this).find(".checkbox").prop("checked")) {
            $(this).css("color", "black");
            $(this).find(".checkbox").prop("checked", false);
            if (checked.id.match(/^instructor_faults.*$/)) {
                $(".student_check" + checked.id.substr(18, 2))
                    .parent()
                    .css("color", "black");
                $(".student_check" + checked.id.substr(18, 2)).prop("checked", false);
                $(".student_check" + checked.id.substr(18, 2)).prop("readonly", false);
            }
        } else {
            $(this).css("color", "red");
            $(this).find(".checkbox").prop("checked", true);
            if (checked.id.match(/^instructor_faults.*$/)) {
                $(".student_check" + checked.id.substr(18, 2))
                    .parent()
                    .css("color", "red");
                $(".student_check" + checked.id.substr(18, 2)).prop("checked", true);
                $(".student_check" + checked.id.substr(18, 2)).prop("readonly", true);
            }
        }
    });
}

function addStudentForward(
    schedule,
    special_days,
    no_school,
    saturdays,
    students,
    is_first_to_third_year
) {
    $("#" + index_count).hide();
    index_count++;
    $("#" + index_count).show();
    if (index_count == students.length - 1) {
        $("#next-student-button").remove();
    }
    if (index_count == 1 && index_count <= students.length) {
        //var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>";
        $(
            "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>"
        ).insertBefore("#next-student-button");
        $("#previous-student-button").click(function () {
            addStudentBackward(
                schedule,
                special_days,
                no_school,
                saturdays,
                students,
                is_first_to_third_year
            );
        });
    }
    $("#frequency > tbody > tr > td:first-child").width("30em");
}

function addStudentBackward(
    schedule,
    special_days,
    no_school,
    saturdays,
    students,
    is_first_to_third_year
) {
    if (index_count == students.length - 1 && index_count > 0) {
        //var previous_student_button = "<a id='previous-student-button' class='btn btn-icon btn-small btn-primary glyphicons left_arrow'>Aluno anteior<i></i></a>";
        $(
            "<a id='next-student-button' class='btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>"
        ).insertAfter("#previous-student-button");
        $("#next-student-button").click(function () {
            addStudentForward(
                schedule,
                special_days,
                no_school,
                saturdays,
                students,
                is_first_to_third_year
            );
        });
    }
    $("#" + index_count).hide();
    index_count--;
    $("#" + index_count).show();

    if (index_count == 0) {
        $("#previous-student-button").remove();
    }
    $("#frequency > tbody > tr > td:first-child").width("30em");
}

const dataFrequency = (index = 0) => {
    var disciplineSelected = $("#disciplines").val();
    var hasSchedules = false;
    let report = JSON.parse(window.localStorage.getItem("frequency")).report;
    let qdtDaysMonth = JSON.parse(window.localStorage.getItem("frequency"))
        .qdtDaysMonth;

    $("#frequency").html("");

    let students = Object.keys(report);
    var content = "<thead>";
    content += "<tr> <th colspan=29>Aluno: " + students[index] + "</th> </tr>";
    content += "<tr>" + "<th>Dia</th>";
    for (i = 1; i <= qdtDaysMonth; i++) {
        content += "<th>" + i + "</th>";
    }
    content += "</tr>";
    content += "</thead>";
    content += "<tbody>";
    for (h = 0; h <= 9; h++) {
        content += "<tr>";
        content += "<th>" + (h + 1) + "º Horário</th>";

        for (d = 1; d <= qdtDaysMonth; d++) {
            if (
                report[students[index]]?.shedules &&
                report[students[index]].shedules[d] != undefined &&
                report[students[index]].shedules[d][h] != null
            ) {
                let red = "";

                report[students[index]].faults.forEach(function (item) {
                    if (parseInt(item.day) == d && parseInt(item.schedule) == h + 1) {
                        red = "red";
                        return true;
                    }
                });

                content +=
                    "<td class='" +
                    red + " available-frequency "
                    + (disciplineSelected !== "Todas as disciplinas" && report[students[index]].shedules[d][h].disciplineId.toString() !== disciplineSelected ? "hidden-and-blocked-frequency" : "") +
                    "' data-student='" +
                    students[index] +
                    "' data-day='" +
                    d +
                    "' data-schedule='" +
                    (h + 1) +
                    "' data-student_fk='" +
                    report[students[index]].id +
                    "' data-discipline_fk='" +
                    report[students[index]].shedules[d][h].disciplineId +
                    "' data-classroom='" +
                    $("#classroom").val() +
                    "' data-month='" +
                    report[students[index]].month +
                    "'>" +
                    "<p class='nameDisciplineFrequency'>" +
                    report[students[index]].shedules[d][h].disciplineName +
                    "</p>" +
                    "<p class='nameInstructorFrequency'>" +
                    report[students[index]].shedules[d][h]["instructorInfo"].name +
                    "</p>" +
                    "</td>";
            } else {
                content += "<td></td>";
            }
            if (report[students[index]].shedules !== undefined) {
                hasSchedules = true;
            }
        }

        content += "</tr>";
    }
    content += "</tbody>";
    $("#frequency").append(content);
    $("#buttonsNexrPrev").html("");

    let buttons = "";

    if (index > 0) {
        buttons +=
            "<a id='prev-student' data-page=" +
            (index - 1) +
            " class='buttonNextPrev btn btn-icon btn-small btn-primary glyphicons left_arrow mr-15'>Aluno anteior<i></i></a>";
    }

    if (index != students.length - 1) {
        buttons +=
            "<a id='next-student' data-page=" +
            (index + 1) +
            " class='buttonNextPrev btn btn-icon btn-small btn-primary glyphicons right_arrow'>Próximo aluno<i></i></a>";
    }

    $("#buttonsNexrPrev").append(buttons);

    hasSchedules
        ? $(".alert-incomplete-data").hide()
        : $(".alert-incomplete-data").show();
};

$(document).on("click", ".buttonNextPrev", function () {
    dataFrequency($(this).data("page"));
});

$(document).on("click", "#frequency > tbody > tr > td", function () {
    if (!$(this).hasClass("hidden-and-blocked-frequency") && $(this).children().length) {
        let obj = $(this);
        jQuery.ajax({
            type: "POST",
            url: getClassesURLSave,
            cache: false,
            data: {
                day: $(this).data("day"),
                month: $(this).data("month"),
                schedule: $(this).data("schedule"),
                discipline: $(this).data("discipline_fk"),
                student: $(this).data("student_fk"),
                classroom: $("#classroom").val(),
            },
            success: function (response) {
                if (response) {
                    if (response == 1) {
                        let store = JSON.parse(window.localStorage.getItem("frequency"));
                        if (obj.hasClass("red")) {
                            $.each(store.report[obj.data("student")].faults, function(index, item) {
                                if (Number(item.day) == Number(obj.data("day")) && Number(item.schedule) == Number(obj.data("schedule"))) {
                                    store.report[obj.data("student")].faults.splice(index, 1);
                                }
                            });
                            obj.removeClass("red");
                        } else {
                            store.report[obj.data("student")].faults.push({
                                day: obj.data("day"),
                                classtype: "",
                                schedule: obj.data("schedule"),
                            });
                            obj.addClass("red");
                        }

                        window.localStorage.setItem("frequency", JSON.stringify(store));
                    }
                }
            },
        });
    }
});

$("#classesSearch").on("click", function () {
    if ($("#classroom").val() !== "" && $("#month").val() !== "") {
        $(".alert-no-classroom-and-month").hide();
        jQuery.ajax({
            type: "GET",
            url: getClassesURL,
            cache: false,
            data: {
                classroom: $("#classroom").val(),
                month: $("#month").val(),
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.valid) {
                    if (response) {
                        window.localStorage.setItem("frequency", response);
                        dataFrequency();
                        $("#widget-frequency").show();
                    }
                } else {
                    $("#widget-frequency").hide();
                    $("#buttonsNexrPrev").html("");
                    $(".alert-incomplete-data").show();
                }
            },
        });
    } else {
        $(".alert-no-classroom-and-month").show();
        $("#widget-frequency").hide();
        $("#buttonsNexrPrev").html("");
    }
});

$("#classroom").on("change", function () {
    $("#disciplines").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        if ($("#classroom > option:selected").attr("showdisciplines") === "1") {
            $.ajax({
                type: "POST",
                url: "?r=classes/getDisciplines",
                cache: false,
                data: {
                    classroom: $("#classroom").val(),
                },
                success: function (response) {
                    $("#disciplines").val("").trigger
                    $("#disciplines").html(response).show();
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
