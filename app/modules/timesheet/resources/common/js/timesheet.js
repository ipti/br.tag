function changeNameLength(name, limit) {
    return (name.length > limit) ? name.substring(0, limit - 3) + "..." : name;
}

function getTimesheet(data) {
    data = $.parseJSON(data);
    if (data.valid == null) {
        $(".table-timesheet").hide();
        $(".schedule-info").addClass("display-hide");
        $("#turn").hide();
    } else if (!data.valid) {
        $(".schedule-info").removeClass("display-hide");
        $(".table-timesheet").hide();
        $("#turn").hide();
    } else {
        $(".schedule-info").removeClass("display-hide");
        $(".table-timesheet").show();
        $("#turn").show();
        generateTimesheet(data.schedules);

    }
}

$(document).on("click", ".btn-generate-timesheet", function () {
    var classroom = $("select.classroom-id").val();
    $.ajax({
        'url': generateTimesheetURL ,
        'type': 'POST',
        'data': {
            'classroom': classroom,
        },
    }).success(function(result){
        getTimesheet(result);
    });
});

function generateTimesheet(data){
    var i = 0;
    var turn = 0;

    $.each(data,function(weekDay, schedules){
        $.each(schedules,function(schedule,info){
            if(i == 0){
                if(info.turn == 0) turn = "Manhã";
                if(info.turn == 1) turn = "Tarde";
                if(info.turn == 2) turn = "Noite";
                i++;
            }
            var discipline = changeNameLength(info.disciplineName, 20);
            var instructor = changeNameLength(info.instructorInfo.name, 30);
            var icons = "";
            if(info.instructorInfo.unavailable) icons += "<i title='Horário indisponível para o instrutor.' class='unavailability-icon fa fa-times-circle'></i>";
            if(info.instructorInfo.countConflicts > 1) icons += "<i title='Instrutor possui "+info.instructorInfo.countConflicts+" conflitos neste horário.' class='fa fa-exclamation-triangle conflict-icon'></i>";

            $(".table-timesheet tr#h"+schedule+" td[week_day="+weekDay+"]").html(
                "<p class='discipline-name' discipline_id='"+info.disciplineId+"' title='"+info.disciplineName+"'>"+discipline+"</p>" +
                "<p class='instructor-name' title='"+info.instructorName+"'>"+instructor+"</p>" +
                icons);
        });
    });

    $("#turn").text(turn);
}