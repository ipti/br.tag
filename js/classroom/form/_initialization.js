$(document).ready(function () {
    $("#js-t-sortable").sortable();

    $(document).ready(function () {
        if ($("#Classroom_complementary_activity").is(":checked")) {
            $("#complementary_activity").show();
        } else {
            $("#complementary_activity").hide();
        }
        if ($("#Classroom_pedagogical_mediation_type").val() === "1" || $("#Classroom_pedagogical_mediation_type").val() === "2") {
            $("#diff_location_container").show();
        } else {
            $("#diff_location_container").hide();
        }
    });
});

//Ao clicar ENTER no formulário adicionar aula
$('#create-dialog-form, #teachingdata-dialog-form, #update-dialog-form').keypress(function (e) {
    if (e.keyCode === $.ui.keyCode.ENTER) {
        e.preventDefault();
    }
});

$('.heading-buttons').css('width', $('#content').width());

$(document).on("click", "#newTeacherDiscipline", function () {
    $("#addTeacherSchoolComponent").modal("show");
});

$(".update-classroom-to-sedsp").click(function() {
    $("#importClassroomToSEDSP").modal("show");
});

$(".import-classroom-button").click(function() {
    $("#importClassroomToSEDSP").find("form").submit();
});
