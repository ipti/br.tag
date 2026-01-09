$("form").on("submit", function (event) {
    let isValid = true;
    let errors = [];
    let mensage = "";
    let password = document.querySelector("#password").value;
    let confirmPassword = document.querySelector("#Confirm").value;

    $(".js-field-required").each(function () {
        if ($(this).is("input, select, textarea") && !$(this).val() && $(this).hasClass("js-ignore-validation") === false) {
            isValid = false;

            let labelText = $(this).closest(".t-field-text").find("label").text().trim();

            errors.push(labelText.replace(/\*/g, ""));
        }
    });


    if (password.length < 6 && password.length > 0) {
        isValid = false;
        mensage += "Senha deve possuir no mínimo 6 caracteres <br> ";
    }

    if (password !== confirmPassword && password.length > 0 && confirmPassword.length > 0) {
        isValid = false;
        mensage += "Senha e Confirmação de Senha não coincidem <br> ";

    }



    if (!isValid) {
        event.preventDefault();
        errors.forEach(function (fieldName) {
            mensage += "O campo <b>" + fieldName + "</b> é obrigatório.<br>";
        });

        $(".js-alert").html(mensage).show();
    } else {
        $(".js-alert").html('').hide();
    }
})

$(document).ready(function () {
    $("#showPassword").click(function () {
        var senhaInput = document.querySelector("#password");
        var senhaInputType = senhaInput.type;
        if (senhaInputType === 'password') {
            senhaInput.type = "text"
        } else {
            senhaInput.type = 'password';
        }


        if (senhaInputType === "password") {
            $(this).removeClass('t-icon-eye').addClass('t-icon-eye_hash');
        } else {
            $(this).removeClass('t-icon-eye_hash').addClass('t-icon-eye');
        }
    });

    $("#showPasswordConfirm").click(function () {
        var senhaInputConfirm = document.querySelector("#Confirm");
        var senhaInputTypeConfirm = senhaInputConfirm.type;
        if (senhaInputTypeConfirm === 'password') {
            senhaInputConfirm.type = "text"
        } else {
            senhaInputConfirm.type = 'password';
        }
        if (senhaInputTypeConfirm === "password") {
            $(this).removeClass('t-icon-eye').addClass('t-icon-eye_hash');
        } else {
            $(this).removeClass('t-icon-eye_hash').addClass('t-icon-eye');
        }
    });
});

$(form + 'password').focusout(function () {
    console.log($(this).attr("id"))
    var id = '#' + $(this).attr("id");
    if (!validatePassword($(id).val())) {
        addError(id, "O campo deve possuir no mínimo 6 caracteres.");
    } else {
        removeError(id);
    }
});

$('#Confirm').focusout(function () {
    var id = '#' + $(this).attr("id");
    var passId = form + 'password';

    if ($(id).val() != $(passId).val()) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

