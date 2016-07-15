$(form+'name').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateSchoolName($(id).val())) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 4.");
    } else {
        removeError(id);
    }
});

$(form + 'username').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validateLogin($(id).val())) {
        $(id).attr('value', '');
        addError(id, "O campo deve possuir no mínimo 4 caracteres.");
    } else {
        removeError(id);
    }
});

$(form + 'password').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validatePassword($(id).val())) {
        $(id).attr('value', '');
        addError(id, "O campo deve possuir no mínimo 6 caracteres.");
    } else {
        removeError(id);
    }
});

$('#Confirm').focusout(function() {
    var id = '#' + $(this).attr("id");
    var passId = form + 'password';

    if ($(id).val() != $(passId).val()) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});