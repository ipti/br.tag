$(document).ready(function() {
    $("#showPassword").click(function() {
        var senhaInput = $("#LoginForm_password");
        if (senhaInput.attr("type") === "password") {
            senhaInput.attr("type", "text");
            $(this).removeClass('t-icon-eye').addClass('t-icon-eye_hash');
        } else {
            senhaInput.attr("type", "password");
            $(this).removeClass('t-icon-eye_hash').addClass('t-icon-eye');
        }
    });
});