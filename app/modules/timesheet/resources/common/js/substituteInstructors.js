$("#classrooms").on("change", function() {
    loadDisciplinesFromClassroom();
    // loadFrequency();
})

$("#month").on("change", function() {
    loadFrequency();
})

$("#instructor").on("change", function() {
    loadFrequency();
})

$("#disciplines").on("change", function () {
    loadFrequency();
})

function loadFrequency(){

    const isMinorClass = $('.disciplines-field').hasClass('hide');
    const isMajorClassValid = !isMinorClass && $('#disciplines').val() !== "";

    if (
        $('#classrooms').val() !== "" &&
        $("#instructor").val() !== "" &&
        $("#month").val() !== "" &&
        (isMinorClass || isMajorClassValid)
    ){
        console.log("bateu aqui");
            $.ajax({
                type: "POST",
                url: "?r=timesheet/timesheet/getFrequency",
                cache: false,
                data: {
                    instructor: $("#instructor").val(),
                    classroom: $("#classrooms").val(),
                    month: $("#month").val(),
                    year: $('#schoolyear').html(),
                    discipline: $('#disciplines').val(),
                },
                beforeSend: function () {
                    $(".loading-frequency").css("display", "inline-block");
                    $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
                    $("#classrooms, #month, #instructor, #disciplines").attr("disabled", "disabled");
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.valid) {
                        var html = "";
                        html += "" +
                        "<table class='t-accordion table-frequency table table-bordered table-striped table-hover'>" +
                        "<thead class='t-accordion__head'>" +
                        "<tr><th class='table-title' colspan='" + (Object.keys(data.response.schedules).length + 1) + "'>" + "Dias de Aula</th></tr>";
                        var daynameRow = "";
                        var dayRow = "";
                        var scheduleRow = "";
                        var checkboxRow = "";
                        const instructorId = data.instructorId;
                        const schedules = data.response.schedules;
                        $.each(schedules, function() {
                            dayRow += "<th>" + (pad(this.day, 2) + "/" + pad($("#month").val(), 2)) + "</th>";
                            daynameRow += "<th>" + this.week_day + "</th>";
                            if (!data.isMinor){
                                scheduleRow += "<th>" + this.schedule + "º Horário</th>";
                            }
                            checkboxRow += "<th class='frequency-checkbox-general frequency-checkbox-container'><input class='frequency-checkbox-substitute' type='checkbox' " + "classroomId='" + $("#classrooms").val() + "' day='" + this.day + "' month='" + $("#month").val() + "' schedule='" + this.schedule + "' /></th>";
                        });
                        html += "<tr class='day-row'>" + dayRow + "</tr><tr class='dayname-row'>" + daynameRow + "</tr>" + ("<tr class='schedule-row'>" + scheduleRow + "</tr>");
                        html += "</thead><tbody class='t-accordion__body'><tr>";
                        $.each(schedules, function() {
                            html += "<td class='frequency-checkbox-instructor" + "'><input class='frequency-checkbox-substitute' type='checkbox' " + " " + (this.class_day ? "checked" : "") + " classroomId='" + $("#classrooms").val() + "' instructorId='" + instructorId + "' day='" + this.day + "' month='" + $("#month").val() + "' schedule='" + this.idSchedule + "'>" + "</td>";
                        })
                        html += "</tr></tbody></table>";
                        $("#frequency-container").html(html).show();
                        $(".frequency-checkbox-general").each(function () {
                            var day = $(this).find(".frequency-checkbox-substitute").attr("day");
                            $(this).find(".frequency-checkbox-substitute").prop("checked", $(".frequency-checkbox-instructor .frequency-checkbox-substitute[day=" + day + "]:checked").length === $(".frequency-checkbox-instructor .frequency-checkbox-substitute[day=" + day + "]").length);

                        });
                        $('[data-toggle="tooltip"]').tooltip({ container: "body" });
                    } else {
                        $("#frequency-container").hide();
                        $(".alert-incomplete-data").html(data.error).show();
                    }
                }, complete: function (response) {
                    $(".loading-frequency").hide();
                    $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
                    $("#classrooms, #month, #disciplines, #instructor").removeAttr("disabled");
                },
            })
        } else {
            $(".alert-required-fields").show();
            $("#frequency-container, .alert-incomplete-data").hide();
        }
}

function loadDisciplinesFromClassroom(){
    const classroom = $('#classrooms').val();
    if(classroom !== "") {
        $.ajax({
            type: "POST",
            url: "?r=timesheet/timesheet/getDisciplines",
            cache: false,
            data: {
                classroom: classroom,
            },
            beforeSend: function () {
                $(".js-loading-div").css("display", "inline-block")
                $('#classrooms').attr("disabled", "disabled");
                $('#disciplines').attr("disabled", "disabled");
            },
            success: function (response) {

                data = JSON.parse(response);

                disciplines = data.disciplines;

                if (data.isMinor == false) {
                    $('.disciplines-field').removeClass('hide');
                }

                if(data.isMinor) {
                    $('.disciplines-field').addClass('hide');
                }

                if (disciplines === "") {
                    $('#disciplines').html(
                        "<option value='-1'> Não há matriz curricular</option>"
                    ).show();
                    $("#classroom").select2("val", "-1");
                }

                if (disciplines !== "") {
                    $("#disciplines").html(decodeHtml(disciplines)).show();
                    $("#disciplines").select2("val", "-1");
                }
            },
            complete: function() {
                $(".js-loading-div").hide();
                $('#disciplines').removeAttr("disabled");
                $('#classrooms').removeAttr("disabled");
            }
        })
    }
}

$(document).on("change", ".frequency-checkbox-substitute", function () {
    let checkbox = this;
    let isSave, route;

    if ($(this).is(":checked")){
        route = "?r=timesheet/timesheet/addSubstituteInstructorDay";
        isSave = 1;
    }

    if (!$(this).is(":checked")){
        route = "?r=timesheet/timesheet/deleteSubstituteInstructorDay";
    }

    $.ajax({
        type: "POST",
        url: route,
        cache: false,
        data: {
            classroomId: $("#instructor").val(),
            day: $(this).attr("day"),
            month: $(this).attr("month"),
            schedule: $(this).attr("schedule"),
            instructorId: $("#instructor").val(),
        },
        beforeSend: function () {
            $(".loading-frequency")
            $(".table-frequency")
            $("#classrooms, #month, #disciplines, #instructor").attr("disabled", "disabled")
        },
        complete: function (response) {
            if ($(checkbox).attr("instructorId") === undefined) {
                $(".table-frequency tbody .frequency-checkbox-substitute[day=" + $(checkbox).attr("day") + "][schedule=" + $(checkbox).attr("schedule") + "]").prop("checked", $(checkbox).is(":checked"));
            }
            $(".loading-frequency").hide();
            $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
            $("#classrooms, #month, #disciplinas, #instructor").removeAttr("disabled");
        },
    });
});
