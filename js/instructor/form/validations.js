$(formInstructorIdentification + 'name,' + formInstructorIdentification + 'filiation_1').on('focusout', function() {
    var id = '#' + $(this).attr("id");

    $(this).val($(this).val().toUpperCase());

    var validation = validateNamePerson(this.value);
    if (!validation[0]) {
        $(id).attr('value', '');
        addError(id, validation[1]);
    } else {
        removeError(id);
    }

});
$(formInstructorIdentification + 'email').on('focusout', function() {
    var id = '#' + $(this).attr("id");

    $(id).val($(id).val().toUpperCase());

    if (!validateEmail($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Digite um e-mail válido.");
    } else {
        removeError(id);
    }

});

$(formInstructorIdentification + 'birthday_date').on('focusout', function() {
    var id = '#' + $(this).attr("id");
    var birthday = stringToDate($(id).val());


    if (!validateDate($(id).val()) || !validateYear(birthday.year)) {
        $(id).attr('value', '');
        addError(id, "O campo deve possuir apenas números, seguindo a estrutura Dia/Mês/Ano. Deve possuir um ano inferior ao ano atual.");
    } else {
        removeError(id);
    }
});

$(formInstructorIdentification + 'nationality').on('change', function() {
    var nationality = $(this).val();
    var nation = $(formInstructorIdentification + 'edcenso_nation_fk');
    var uf = $(formInstructorIdentification + 'edcenso_uf_fk');
    var city = $(formInstructorIdentification + 'edcenso_city_fk');

    var nationVal = (nationality == 2 || nationality == 1) ? '76' : nation.val();
    var ufVal = (nationality == 3) ? "" : uf.val();
    var cityVal = (nationality == 3) ? "" : city.val();

    nation.val(nationVal).trigger('change')
            .select2('readonly', (nationality == 2 || nationality == 1));
    uf.val(ufVal).trigger('change')
            .select2('readonly', (nationality == 3));
    city.val(cityVal).trigger('change')
            .select2('readonly', (nationality == 3));

    nation.select2('enable', !(nationality == ""));
    uf.select2('enable', !(nationality == ""));
    city.select2('enable', !(nationality == ""));
});


$(formInstructorIdentification + 'deficiency').on('change', function() {
    if ($(this).is(':checked')) {
        $("#InstructorIdentification_deficiencies input").removeAttr('disabled');
    } else {
        $("#InstructorIdentification_deficiencies input").removeAttr('checked').add().attr('disabled', 'disabled');
    }
});


$(formInstructorIdentification + 'deficiency_type_blindness').on('click', function() {
    if (($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            ) {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').add().attr('checked', 'checked');
    } else {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').removeAttr('checked', 'checked');
    }

    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_low_vision').add().attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_deafness').add().attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').add().attr('disabled', 'disabled');
    } else {
        $(formInstructorIdentification + 'deficiency_type_low_vision').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
    }
});

$(formInstructorIdentification + 'deficiency_type_low_vision').on('click', function() {
    if (($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            ) {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').add().attr('checked', 'checked');
    } else {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').removeAttr('checked', 'checked');
    }
    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_blindness').add().attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').add().attr('disabled', 'disabled');
    } else {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
    }
});

$(formInstructorIdentification + 'deficiency_type_deafness').on('click', function() {
    if (($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            ) {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').add().attr('checked', 'checked');
    } else {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').removeAttr('checked', 'checked');
    }
    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_blindness').add().attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').add().attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').add().attr('disabled', 'disabled');
    } else {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
    }
});

$(formInstructorIdentification + 'deficiency_type_disability_hearing').on('click', function() {
    if (($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            ) {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').add().attr('checked', 'checked');
    } else {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').removeAttr('checked', 'checked');
    }
    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_deafness').add().attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').add().attr('disabled', 'disabled');
    } else {
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');

    }
});
$(formInstructorIdentification + 'deficiency_type_deafblindness').on('click', function() {
    if (($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            ) {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').add().attr('checked', 'checked');
    } else {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').removeAttr('checked', 'checked');
    }

    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_blindness').add().attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_low_vision').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_low_vision').add().attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_deafness').add().attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').removeAttr('checked', 'checked');
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').add().attr('disabled', 'disabled');
    } else {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_low_vision').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').removeAttr('disabled');
    }
});

$(formInstructorIdentification + 'deficiency_type_phisical_disability').on('click', function() {
    if (($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            ) {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').add().attr('checked', 'checked');
    } else {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').removeAttr('checked', 'checked');
    }
});

$(formInstructorIdentification + 'deficiency_type_intelectual_disability').on('click', function() {
    if (($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_deafness').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') && $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked'))
            || ($(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked') && $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked'))
            ) {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').add().attr('checked', 'checked');
    } else {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').removeAttr('checked', 'checked');
    }
});

var formDocumentsAndAddress = '#InstructorDocumentsAndAddress_';

$(formDocumentsAndAddress + 'cpf').on('change', function() {
    var id = '#' + $(this).attr("id");

    $(id).val($(id).val().toUpperCase());

    if (!validateCpf($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'cep').focusout(function() {
    var name = $(this).attr("id");
    var id = '#' + name;
    var element = $(id);

    var form = formDocumentsAndAddress.replace('#','');

    var address = $("label[for="+form+"address]");
    var neighborhood = $("label[for="+form+"neighborhood]");
    var edcenso_uf_fk = $("#"+form+"edcenso_uf_fk");
    var edcenso_city_fk = $("#"+form+"edcenso_city_fk");

    var noError = false;
    var required = false;

    element.val(element.val().toUpperCase());

    noError = validateCEP(element.val()) || !(element.val().length > 0);
    required = validateCEP(element.val()) && noError;


    if(noError){
        removeError(id);
    }else{
        element.attr('value', '');
        addError(id, "Informe um CEP cadastrado nos correios. Apenas números são aceitos. Deve possuir no máximo 8 caracteres.");
    }

    if(required){
        addRequired(address);
        addRequired(neighborhood);
        addRequiredSelect2(edcenso_uf_fk);
        addRequiredSelect2(edcenso_city_fk);
    }else{
        removeRequired(address);
        removeRequired(neighborhood);
        removeRequiredSelect2(edcenso_uf_fk);
        removeRequiredSelect2(edcenso_city_fk);
    }

    //if ($(id).val().length == 0) {
    //    $(formDocumentsAndAddress + 'edcenso_uf_fk').val("").trigger('change').select2("readonly", false);
    //    $(formDocumentsAndAddress + 'edcenso_city_fk').val("").trigger('change').select2("readonly", false);
    //}
});

$(formDocumentsAndAddress + 'address').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());

    if (!validateInstructorAddress($(id).val())) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'address_number').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());

    if (!validateInstructorAddressNumber($(id).val())) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1.");
    } else {
        removeError(id);
    }
});
$(formDocumentsAndAddress + 'neighborhood').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());

    if (!validateInstructorAddressNeighborhood($(this).val())) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1.");
    } else {
        removeError(id);
    }
});


$('#InstructorVariableData_high_education_initial_year_1, \n\
    #InstructorVariableData_high_education_initial_year_2,\n\
    #InstructorVariableData_high_education_initial_year_3').on('change', function() {
    if (this.value.length == 4) {
        var data = new Date();
        if (!anoMinMax(2002, data.getFullYear(), this.value)) {
            $(this).attr('value', '');
        }
    }
});
$('#InstructorVariableData_high_education_final_year_1,\n\
    #InstructorVariableData_high_education_final_year_2,\n\
    #InstructorVariableData_high_education_final_year_3').on('change', function() {
    if (this.value.length == 4) {
        var data = new Date();
        if (!anoMinMax(1941, data.getFullYear(), this.value)) {
            $(this).attr('value', '');
        }
    }
});

$(formInstructorvariableData + 'scholarity').on('change', function() {
    if ($(this).val() == 6) {
        $("#instructorVariableData").show();
        $("#tab-instructor-data1").attr('class', 'active sub-active');
        $("#instructor-data1").attr('class', 'active sub-active');
        $("#instructor-data2").hide();
        $("#instructor-data3").hide();
        $(formInstructorvariableData + 'high_education_situation_1').removeAttr('disabled');
        $(formInstructorvariableData + 'high_education_formation_1').removeAttr('disabled');
        $(formInstructorvariableData + 'high_education_course_code_1_fk').removeAttr('disabled');

        $(formInstructorvariableData + 'high_education_final_year_1').removeAttr('disabled');
        $(formInstructorvariableData + 'high_education_institution_code_1_fk').removeAttr('disabled');

        $(formInstructorvariableData + 'high_education_situation_2').removeAttr('disabled');
        $(formInstructorvariableData + 'high_education_formation_2').removeAttr('disabled');
        $(formInstructorvariableData + 'high_education_course_code_2_fk').removeAttr('disabled');

        $(formInstructorvariableData + 'high_education_final_year_2').removeAttr('disabled');
        $(formInstructorvariableData + 'high_education_institution_code_2_fk').removeAttr('disabled');

        $(formInstructorvariableData + 'high_education_situation_3').removeAttr('disabled');
        $(formInstructorvariableData + 'high_education_formation_3').removeAttr('disabled');
        $(formInstructorvariableData + 'high_education_course_code_3_fk').removeAttr('disabled');

        $(formInstructorvariableData + 'high_education_final_year_3').removeAttr('disabled');
        $(formInstructorvariableData + 'high_education_institution_code_3_fk').removeAttr('disabled');

        $(formInstructorvariableData + 'post_graduation_specialization').removeAttr('disabled');
        $(formInstructorvariableData + 'post_graduation_master').removeAttr('disabled');
        $(formInstructorvariableData + 'post_graduation_doctorate').removeAttr('disabled');
        $(formInstructorvariableData + 'post_graduation_none').removeAttr('disabled');

        $(formInstructorvariableData + 'other_courses_nursery').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_pre_school').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_basic_education_initial_years').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_basic_education_final_years').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_high_school').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_education_of_youth_and_adults').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_special_education').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_native_education').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_field_education').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_environment_education').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_human_rights_education').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_sexual_education').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_child_and_teenage_rights').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_ethnic_education').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_other').removeAttr('disabled');
        $(formInstructorvariableData + 'other_courses_none').removeAttr('disabled');

    } else {

        $("#instructorVariableData").hide();
        $(formInstructorvariableData + 'high_education_situation_1').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_formation_1').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_course_code_1_fk').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_initial_year_1').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_final_year_1').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_institution_code_1_fk').add().attr('disabled', 'disabled');

        $(formInstructorvariableData + 'high_education_situation_2').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_formation_2').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_course_code_2_fk').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_initial_year_2').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_final_year_2').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_institution_code_2_fk').add().attr('disabled', 'disabled');

        $(formInstructorvariableData + 'high_education_situation_3').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_formation_3').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_course_code_3_fk').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_initial_year_3').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_final_year_3').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_institution_code_3_fk').add().attr('disabled', 'disabled');

        $(formInstructorvariableData + 'post_graduation_specialization').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'post_graduation_master').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'post_graduation_doctorate').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'post_graduation_none').add().attr('disabled', 'disabled');

        $(formInstructorvariableData + 'other_courses_nursery').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_pre_school').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_basic_education_initial_years').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_basic_education_final_years').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_high_school').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_education_of_youth_and_adults').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_special_education').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_native_education').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_field_education').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_environment_education').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_human_rights_education').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_sexual_education').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_child_and_teenage_rights').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_ethnic_education').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_other').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'other_courses_none').add().attr('disabled', 'disabled');


    }

    $(formInstructorvariableData + 'high_education_situation_1').on('change', function() {
        if ($(this).val() == 1) { // Concluído
            $(formInstructorvariableData + 'high_education_initial_year_1').add().attr('disabled', 'disabled');
            $(formInstructorvariableData + 'high_education_final_year_1').removeAttr('disabled');
        } else { // Em Andamento
            $(formInstructorvariableData + 'high_education_initial_year_1').removeAttr('disabled');
            $(formInstructorvariableData + 'high_education_final_year_1').add().attr('disabled', 'disabled');
        }

    });

    $(formInstructorvariableData + 'high_education_situation_2').on('change', function() {
        if ($(this).val() == 1) { // Concluído
            $(formInstructorvariableData + 'high_education_initial_year_2').add().attr('disabled', 'disabled');
            $(formInstructorvariableData + 'high_education_final_year_2').removeAttr('disabled');
        } else { // Em Andamento
            $(formInstructorvariableData + 'high_education_initial_year_2').removeAttr('disabled');
            $(formInstructorvariableData + 'high_education_final_year_2').add().attr('disabled', 'disabled');
        }

    });

    $(formInstructorvariableData + 'high_education_situation_3').on('change', function() {
        if ($(this).val() == 1) { // Concluído
            $(formInstructorvariableData + 'high_education_initial_year_3').add().attr('disabled', 'disabled');
            $(formInstructorvariableData + 'high_education_final_year_3').removeAttr('disabled');
        } else { // Em Andamento
            $(formInstructorvariableData + 'high_education_initial_year_3').removeAttr('disabled');
            $(formInstructorvariableData + 'high_education_final_year_3').add().attr('disabled', 'disabled');
        }

    });

    $(formInstructorvariableData + 'high_education_course_code_1_fk').on('change', function() {
        var course = $(formInstructorvariableData + 'high_education_course_code_1_fk option:selected').text();
        course = course.toUpperCase();
        var beforelicenciatura = course.split('LICENCIATURA')[0];
        if (course != beforelicenciatura) {
            // Se é diferente então encontrou a palavra Licenciatura
            $(formInstructorvariableData + 'high_education_formation_1').add().attr('disabled', 'disabled');
        } else {
            $(formInstructorvariableData + 'high_education_formation_1').removeAttr('disabled');
        }
    });

    $(formInstructorvariableData + 'high_education_course_code_2_fk').on('change', function() {
        var course = $(formInstructorvariableData + 'high_education_course_code_2_fk option:selected').text();
        course = course.toUpperCase();
        var beforelicenciatura = course.split('LICENCIATURA')[0];
        if (course != beforelicenciatura) {
            // Se é diferente então encontrou a palavra Licenciatura
            $(formInstructorvariableData + 'high_education_formation_2').add().attr('disabled', 'disabled');
        } else {
            $(formInstructorvariableData + 'high_education_formation_2').removeAttr('disabled');
        }
    });

    $(formInstructorvariableData + 'high_education_course_code_3_fk').on('change', function() {
        var course = $(formInstructorvariableData + 'high_education_course_code_3_fk option:selected').text();
        course = course.toUpperCase();
        var beforelicenciatura = course.split('LICENCIATURA')[0];
        if (course != beforelicenciatura) {
            // Se é diferente então encontrou a palavra Licenciatura
            $(formInstructorvariableData + 'high_education_formation_3').add().attr('disabled', 'disabled');
        } else {
            $(formInstructorvariableData + 'high_education_formation_3').removeAttr('disabled');
        }
    });
    
    var pgs = formInstructorvariableData + 'post_graduation_specialization';
    var pgm = formInstructorvariableData + 'post_graduation_master';
    var pgd = formInstructorvariableData + 'post_graduation_doctorate';
    var pgn = formInstructorvariableData + 'post_graduation_none';

    $(pgs+','+pgm+','+pgd).on('change', function() {
        var checked = !($(pgs).is(':checked') || $(pgm).is(':checked') || $(pgd).is(':checked'));
        $(pgn).attr('checked',checked);
    });
    $(pgn).on('change', function() {
        if($(pgn).is(':checked')){
            $(pgs+','+pgm+','+pgd).attr('checked',false);
        }
    });
    
    var ocn = formInstructorvariableData + 'other_courses_none';
    
    $('.other_courses').on('change', function() {
        var checked = $('.other_courses').is(':checked');
        $(ocn).attr('checked',checked);
    });
    $(ocn).on('change', function() {
        if($(ocn).is(':checked')){
            $('.other_courses').attr('checked',false);
        }
    });
});

$(".save-instructor").click(function () {
    var error = false;
    var message = "";
    if ($("#InstructorIdentification_name").val() === "") {
        error = true;
        message += "Campo <b>Nome</b> é obrigatório.<br>";
    }
    if ($("#InstructorIdentification_birthday_date").val() === "") {
        error = true;
        message += "Campo <b>Data de Nascimento</b> é obrigatório.<br>";
    }
    if ($("#InstructorIdentification_sex").val() === "") {
        error = true;
        message += "Campo <b>Sexo</b> é obrigatório.<br>";
    }
    if ($("#InstructorIdentification_color_race").val() === "") {
        error = true;
        message += "Campo <b>Cor / Raça</b> é obrigatório.<br>";
    }
    if ($("#InstructorIdentification_filiation").val() === "") {
        error = true;
        message += "Campo <b>Filiação</b> é obrigatório.<br>";
    }
    if ($("#InstructorIdentification_nationality").val() === "") {
        error = true;
        message += "Campo <b>Nacionalidade</b> é obrigatório.<br>";
    }
    if ($("#InstructorIdentification_edcenso_nation_fk").val() === "") {
        error = true;
        message += "Campo <b>País de origem</b> é obrigatório.<br>";
    }
    if ($("#InstructorVariableData_scholarity").val() === "") {
        error = true;
        message += "Campo <b>Escolaridade</b> é obrigatório.<br>";
    }
    if ($("#InstructorDocumentsAndAddress_cep").val() !== "") {
        if ($("#InstructorDocumentsAndAddress_address").val() === "") {
            error = true;
            message += "Com o CEP preenchido, o campo <b>Endereço</b> se torna obrigatório.<br>";
        }
        if ($("#InstructorDocumentsAndAddress_neighborhood").val() === "") {
            error = true;
            message += "Com o CEP preenchido, o campo <b>Bairro / Povoado</b> se torna obrigatório.<br>";
        }
        if ($("#InstructorDocumentsAndAddress_edcenso_uf_fk").val() === "") {
            error = true;
            message += "Com o CEP preenchido, o campo <b>Estado</b> se torna obrigatório.<br>";
        }
        if ($("#InstructorDocumentsAndAddress_edcenso_city_fk").val() === "") {
            error = true;
            message += "Com o CEP preenchido, o campo <b>Cidade</b> se torna obrigatório.<br>";
        }
    }
    if (error) {
        $(this).closest("form").find(".instructor-error").html(message).show();
    } else {
        $(this).closest("form").find(".instructor-error").hide();
        $(this).closest("form").submit();
    }
});