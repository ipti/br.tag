function getTimesheet(data) {
    data = $.parseJSON(data);
    console.log(data);
    if (data.valid == null) {
        $(".table-timesheet").hide();
        $(".btn-generate-timesheet").addClass("display-hide");
    } else if (!data.valid) {
        $(".btn-generate-timesheet").removeClass("display-hide");
        $(".table-timesheet").hide();
    } else {
        $(".btn-generate-timesheet").removeClass("display-hide");
        $(".table-timesheet").show();
    }
}
