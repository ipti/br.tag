function getTimesheet(data) {
    data = $.parseJSON(data);
    if (data.valid == null) {
        $(".table-timesheet").hide();
        $(".schedule-info").addClass("display-hide");
    } else if (!data.valid) {
        $(".schedule-info").removeClass("display-hide");
        $(".table-timesheet").hide();
    } else {
        $(".schedule-info").removeClass("display-hide");
        $(".table-timesheet").show();
    }
}

$(document).on("click", ".btn-generate-timesheet", function () {
    var schedules = $("#schedules").val();
    var classroom = $("select.classroom-id").val();
    if (schedules > 0 && schedules <= 10) {
        $.ajax({
            'url': generateTimesheet ,
            'type': 'POST',
            'data': {
                'classroom': classroom,
                'schedules': schedules
            },
        }).success(function(result){

        });
    }
});
