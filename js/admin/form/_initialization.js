$('#Users_password').attr('value', '');

$(document).on("click", "#addSchool", function () {
    $("#addSchools").modal("show");
});

//update user
if($(".js-show-instructor-input").val() === "instructor"){
    $('.js-instructor-input').show();
}

