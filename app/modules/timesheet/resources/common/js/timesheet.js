function changeNameLength(name, limit) {
    return (name.length > limit) ? name.substring(0, limit - 3) + "..." : name;
}

function getTimesheet(data) {
    data = $.parseJSON(data);
    $(".alert").addClass("display-hide");
    $(".schedule-info").removeClass("display-hide");
    $("#turn").hide();
    $(".table-container").hide();
    if (data.valid == null) {
        $(".schedule-info").addClass("display-hide");
    } else if (!data.valid) {
        if (data.error == "curricularMatrix") {
            $(".alert").removeClass("display-hide");
        }
    } else {
        $(".table-container").show();
        $("#turn").show();
        generateTimesheet(data.schedules);

    }
}

$(document).on("click", ".btn-generate-timesheet", function () {
    var classroom = $("select.classroom-id").val();
    $.ajax({
        'url': generateTimesheetURL,
        'type': 'POST',
        'data': {
            'classroom': classroom,
        },
    }).success(function (result) {
        getTimesheet(result);
    });
});

function generateTimesheet(data) {
    var i = 0;
    var turn = 0;
    $(".table-timesheet tr td").children().remove();
    $.each(data, function (weekDay, schedules) {
        $.each(schedules, function (schedule, info) {
            if (i == 0) {
                if (info.turn == 0) turn = "Manhã";
                if (info.turn == 1) turn = "Tarde";
                if (info.turn == 2) turn = "Noite";
                i++;
            }
            var discipline = changeNameLength(info.disciplineName, 20);
            var instructor = changeNameLength(info.instructorInfo.name, 30);
            var icons = "";
            if (info.instructorInfo.unavailable) icons += "<i title='Horário indisponível para o instrutor.' class='unavailability-icon fa fa-times-circle darkred'></i>";
            if (info.instructorInfo.countConflicts > 1) icons += "<i title='Instrutor possui " + info.instructorInfo.countConflicts + " conflitos neste horário.' class='fa fa-exclamation-triangle conflict-icon darkgoldenrod'></i>";

            $(".table-timesheet tr#h" + schedule + " td[week_day=" + weekDay + "]").html(
                "<div schedule='"+info.id+"' class='schedule-block'>"+
                "<p class='discipline-name' discipline_id='" + info.disciplineId + "' title='" + info.disciplineName + "'>" + discipline + "</p>" +
                "<p class='instructor-name' instructor_id='"+ info.instructorInfo.id +"' title='" + info.instructorInfo.name + "'>" + instructor + "<i class='fa fa-pencil edit-instructor'></i></p>" +
                icons+
                "</div>");
        });
    });

    $("#turn").text(turn);
}


$(document).on("click", ".schedule-selected .instructor-name",function(){
    var instructorId = $(this).attr("instructor_id");
    var disciplineId = $(this).parent().find(".discipline-name").attr("discipline_id");
    var scheduleId = $(this).parent().attr("schedule");

    $.ajax({
        'url': getInstructorsUrl,
        'type': 'POST',
        'data': {
            'discipline':disciplineId
        },
    }).success(function (result) {
        $("#change-instructor-schedule").val(scheduleId);
        $("#change-instructor-id").html(result);
        $("#change-instructor-id").val(instructorId).select2();
        $("#change-instructor-modal").modal();
    });
});
$(document).on("click", "#change-instructor-button", function(){
    $.ajax({
        'url': changeInstructorUrl,
        'type': 'POST',
        'data': {
            'schedule':$("#change-instructor-schedule").val(),
            'instructor':$("#change-instructor-id").val()
        },
    }).success(function (result) {
        getTimesheet(result);
        $("#change-instructor-modal").modal('hide');
    });
});

$(document).on("click", ".table-timesheet td",function(){
    if($(this).hasClass("schedule-selected")){
        $(this).removeClass("schedule-selected");
    }else{
        //Já selecionou alguem
        if($(".table-timesheet").find(".schedule-selected").length > 0){

            var firstSelected = $(".table-timesheet").find(".schedule-selected");
            var secondSelected = $(this);

            var firstSchedule = null;
            var secondSchedule = null;

            if(firstSelected.find(".schedule-block").length > 0){
                firstSchedule = {"id":firstSelected.find(".schedule-block").attr("schedule")};
            }else{
                firstSchedule = {"id":null, "week_day": firstSelected.attr("week_day"), "schedule":firstSelected.parent().attr("id").replace("h","")};
            }
            if(secondSelected.find(".schedule-block").length > 0){
                secondSchedule = {"id":secondSelected.find(".schedule-block").attr("schedule")};
            }else{
                secondSchedule = {"id":null, "week_day": secondSelected.attr("week_day"), "schedule":secondSelected.parent().attr("id").replace("h","")};
            }
            changeSchedule(firstSchedule, secondSchedule);
        }else { //Primeira seleção
            $(this).addClass("schedule-selected");
        }
    }
});

function changeSchedule(firstSchedule, secondSchedule){
    $.ajax({
        'url': changeSchedulesURL,
        'type': 'POST',
        'data': {
            'firstSchedule': firstSchedule,
            'secondSchedule': secondSchedule,
        },
    }).success(function (result) {
        getTimesheet(result);
        $('.schedule-selected').removeClass("schedule-selected");
    });
}