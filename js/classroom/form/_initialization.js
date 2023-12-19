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

$(".update-classroom-from-sedsp").click(function() {
    $("#importClassroomFromSEDSP").modal("show");
});

$(".import-classroom-button").click(function() {
    $("#importClassroomFromSEDSP").find("form").submit();
});

$('#copy-gov-id').click(function() {
    let govId = $('#Classroom_gov_id').val();
    navigator.clipboard.writeText(govId);
    $('#copy-message').text('Copiado!').fadeIn().delay(1000).fadeOut();
});
