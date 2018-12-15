/**
 * Created by IPTIPC100 on 29/06/2016.
 */
$("#save-emails").click(function () {
    var submit = true;
    $("input").each(function () {
        if ($(this).val() != "" && !validateEmail($(this).val())) {
            submit = false;
            $(".alert").show();
            return false;
        }
    });
    if (submit) {
        $(".alert").hide();
        $("#updateEmails-form").submit();
    }
});

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}