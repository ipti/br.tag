$(document).on("click", ".change-active", function () {
    $("#calendar_id").val($(this).data('id'));
});

$(document).on("click", ".remove-calendar", function () {
    $("#calendar_removal_id").val($(this).data('id'));
});