

$('.heading-buttons').css('width', $('#content').width());

$(document).on("change", "#StudentEnrollment_public_transport", function () {
    if ($(this).is(":checked")) {
        $("#transport_responsable, #transport_type").show();
    } else {
        $("#StudentEnrollment_transport_responsable_government").val("").trigger("change.select2");
        $("#transport_type input[type=checkbox]:checked").prop("checked", false);
        $("#transport_responsable, #transport_type").hide();
    }
});
$("#StudentEnrollment_public_transport").trigger("change");