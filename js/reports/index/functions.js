$(document).on("change", "#quartely_report_classroom_student", function () {
    $(".classroom-student-container").hide();
    $(".classroom-student-error").hide();
    const classroom_id = $("#quartely_report_classroom_student").val();
    
    $.ajax({
        type: "GET",
        url: `${window.location.host}/?r=reports/getstudentclassrooms&id=${classroom_id}`,
        success: function (response) {
            $("#student").empty();
            if(response != null && response != '') {
                $("#student").append(response);
                $(".classroom-student-container").show();
            }else {
                $(".classroom-student-error").show();
            }
        }
    });
});