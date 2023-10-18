$(document).on("click", ".unsync", function(e) {
    e.preventDefault();
    $("#syncClassroomToSEDSP").find("form").attr("action", $(this).attr("href"));
    $('#syncClassroomToSEDSP').modal("show");
});

$(document).on("click", ".sync-classroom-button", function() {
    $(this).closest("form").submit();
});