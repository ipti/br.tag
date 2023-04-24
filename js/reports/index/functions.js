$(document).on("change", "#student", function () {
    $(".classroom-student-container").hide();
    $(".classroom-student-error").hide();
    $.ajax({
        type: "POST",
        url: `${window.location.host}/?r=reports/getstudentclassrooms`,
        data: {
            student_id: $("#student").val(),
        },
        success: function (response) {
            if(response != null && response != '') {
                $("#classroom_student").append(response)
                $(".classroom-student-container").show();
            }else {
                $(".classroom-student-error").show();
            }
        }
    });
});