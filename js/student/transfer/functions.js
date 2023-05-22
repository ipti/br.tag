$('.js-select-school-classroom').change(function () {
    var school_id = $("#StudentEnrollment_school_inep_id_fk").val();
    $.ajax({
        url: `${window.location.host}/?r=student/getclassrooms`,
        type: "POST",
        data:{
            inep_id:  school_id
        }
    }).success(function (response) {
        $("#StudentEnrollment_classroom_fk").empty();
        $("#StudentEnrollment_classroom_fk").html(decodeHtml(response));
        $("#StudentEnrollment_classroom_fk").prop("disabled", false);
    }) 
});