$(formIdentification + 'name').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    var ret = validateNamePerson(($(id).val()));
    if (!ret[0]) {
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'nis').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validateNis($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

var date = new Date();
$(formIdentification + 'birthday').mask("99/99/9999");
$(formIdentification + 'birthday').focusout(function() {
    var id = '#' + $(this).attr("id");
    var birthday = stringToDate($(formIdentification + 'birthday').val());
    
    
    if (!validateDate($(formIdentification + 'birthday').val()) || !validateYear(birthday.year)) {
        $(formIdentification + 'birthday').attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'filiation').change(function() {
    $(formIdentification + 'mother_name').attr("disabled", "disabled");
    $(formIdentification + 'father_name').attr("disabled", "disabled");

    if ($(formIdentification + 'filiation').val() == 1) {
        $(formIdentification + 'mother_name').removeAttr("disabled");
        $(formIdentification + 'father_name').removeAttr("disabled");
    }
    else {
        $(formIdentification + 'mother_name').val("");
        $(formIdentification + 'father_name').val("");
    }
});

$(formIdentification + 'mother_name, '
        + formIdentification + 'father_name').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    var ret = validateNamePerson(($(id).val()));
    if (!ret[0]) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }

    if ($(formIdentification + 'mother_name').val() == $(formIdentification + 'father_name').val()) {
        $(formIdentification + 'father_name').attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'nationality').change(function() {
    var nationality = ".nationality-sensitive";
    var br = nationality+".br";
    var nobr = nationality+".no-br";
    $(nationality).attr("disabled", "disabled");
    if ($(this).val() == 3) {
        $(nobr).removeAttr("disabled");
        $(formIdentification + 'edcenso_nation_fk').val(null).trigger('change').select2('readonly', false);
        $(formIdentification + 'edcenso_uf_fk').val("");
        $(formIdentification + 'edcenso_city_fk').val("");
    } else if ($(this).val() == "") {
        $(formIdentification + 'edcenso_nation_fk').val(null).trigger('change').attr("disabled", "disabled");
        $(nationality).attr("disabled", "disabled");
    } else {
        $(formIdentification + 'edcenso_nation_fk').val(76).trigger('change').removeAttr("disabled").select2('readonly', true);
        $(br).removeAttr("disabled");
        $(formDocumentsAndAddress + 'civil_certification').trigger("change");
    }
});

$(formIdentification + "deficiency").change(function() {

    $(formIdentification + 'deficiency_type_blindness').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_low_vision').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_deafness').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_disability_hearing').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_deafblindness').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_phisical_disability').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_intelectual_disability').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_multiple_disabilities').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_autism').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_aspenger_syndrome').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_rett_syndrome').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_childhood_disintegrative_disorder').attr("disabled", "disabled");
    $(formIdentification + 'deficiency_type_gifted').attr("disabled", "disabled");
    $(formIdentification + 'resource_aid_lector').attr("disabled", "disabled");
    $(formIdentification + 'resource_aid_transcription').attr("disabled", "disabled");
    $(formIdentification + 'resource_interpreter_guide').attr("disabled", "disabled");
    $(formIdentification + 'resource_interpreter_libras').attr("disabled", "disabled");
    $(formIdentification + 'resource_lip_reading').attr("disabled", "disabled");
    $(formIdentification + 'resource_zoomed_test_16').attr("disabled", "disabled");
    $(formIdentification + 'resource_zoomed_test_20').attr("disabled", "disabled");
    $(formIdentification + 'resource_zoomed_test_24').attr("disabled", "disabled");
    $(formIdentification + 'resource_braille_test').attr("disabled", "disabled");
    $(formIdentification + 'resource_none').attr("disabled", "disabled");

    if ($(this).is(':checked')) {
        $(formIdentification + 'deficiency_type_blindness').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_low_vision').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_deafness').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_disability_hearing').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_deafblindness').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_phisical_disability').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_intelectual_disability').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_multiple_disabilities').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_autism').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_aspenger_syndrome').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_rett_syndrome').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_childhood_disintegrative_disorder').removeAttr("disabled");
        $(formIdentification + 'deficiency_type_gifted').removeAttr("disabled");
        $(formIdentification + 'resource_aid_lector').removeAttr("disabled");
        $(formIdentification + 'resource_aid_transcription').removeAttr("disabled");
        $(formIdentification + 'resource_interpreter_guide').removeAttr("disabled");
        $(formIdentification + 'resource_interpreter_libras').removeAttr("disabled");
        $(formIdentification + 'resource_lip_reading').removeAttr("disabled");
        $(formIdentification + 'resource_zoomed_test_16').removeAttr("disabled");
        $(formIdentification + 'resource_zoomed_test_20').removeAttr("disabled");
        $(formIdentification + 'resource_zoomed_test_24').removeAttr("disabled");
        $(formIdentification + 'resource_braille_test').removeAttr("disabled");
        $(formIdentification + 'resource_none').removeAttr("disabled");

        $(formIdentification + 'deficiency_type_blindness').val("");
        $(formIdentification + 'deficiency_type_low_vision').val("");
        $(formIdentification + 'deficiency_type_deafness').val("");
        $(formIdentification + 'deficiency_type_disability_hearing').val("");
        $(formIdentification + 'deficiency_type_deafblindness').val("");
        $(formIdentification + 'deficiency_type_phisical_disability').val("");
        $(formIdentification + 'deficiency_type_intelectual_disability').val("");
        $(formIdentification + 'deficiency_type_multiple_disabilities').val("");
        $(formIdentification + 'deficiency_type_autism').val("");
        $(formIdentification + 'deficiency_type_aspenger_syndrome').val("");
        $(formIdentification + 'deficiency_type_rett_syndrome').val("");
        $(formIdentification + 'deficiency_type_childhood_disintegrative_disorder').val("");
        $(formIdentification + 'deficiency_type_gifted').val("");
        $(formIdentification + 'resource_aid_lector').val("");
        $(formIdentification + 'resource_aid_transcription').val("");
        $(formIdentification + 'resource_interpreter_guide').val("");
        $(formIdentification + 'resource_interpreter_libras').val("");
        $(formIdentification + 'resource_lip_reading').val("");
        $(formIdentification + 'resource_zoomed_test_16').val("");
        $(formIdentification + 'resource_zoomed_test_20').val("");
        $(formIdentification + 'resource_zoomed_test_24').val("");
        $(formIdentification + 'resource_braille_test').val("");
        $(formIdentification + 'resource_none').val("");

    }
});

$(formDocumentsAndAddress + 'address').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddress($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'number').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddressNumber($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'complement').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddressComplement($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'neighborhood').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddressNeighborhood($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'cpf').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validateCpf($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'cep').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validateCEP($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'rg_number').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateRG($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'civil_certification').change(function() {

    var oldDocuments = $(formDocumentsAndAddress + 'civil_certification_type'
            + ', ' + formDocumentsAndAddress + 'civil_certification_term_number'
            + ', ' + formDocumentsAndAddress + 'civil_certification_sheet'
            + ', ' + formDocumentsAndAddress + 'civil_certification_book'
            + ', ' + formDocumentsAndAddress + 'civil_certification_date'
            + ', ' + formDocumentsAndAddress + 'notary_office_uf_fk'
            + ', ' + formDocumentsAndAddress + 'notary_office_city_fk'
            + ', ' + formDocumentsAndAddress + 'edcenso_notary_office_fk');

    var newDocument = $(formDocumentsAndAddress + 'civil_register_enrollment_number');


    if ($(this).val() == "") {
        oldDocuments.attr("disabled", "disabled").parent().parent().hide();
        newDocument.attr("disabled", "disabled").parent().parent().hide();
    }
    else {
        oldDocuments.removeAttr("disabled").parent().parent().show();
        newDocument.removeAttr("disabled").parent().parent().show();
        if ($(this).val() == 2) {
            oldDocuments.val("").attr("disabled", "disabled").parent().parent().hide();
        } else {
            newDocument.attr("disabled", "disabled").parent().parent().hide();
        }
    }
});

$(formDocumentsAndAddress + 'rg_number_expediction_date, ' + formDocumentsAndAddress + 'civil_certification_date').mask("99/99/9999");
$(formDocumentsAndAddress + 'rg_number_expediction_date, ' + formDocumentsAndAddress + 'civil_certification_date').focusout(function() {
    var id = '#' + $(this).attr("id");
    var documentDate = stringToDate($(id).val());
    var birthday = stringToDate($(formIdentification + 'birthday').val());
    if (!validateDate($(id).val()) || birthday.asianStr > documentDate.asianStr) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});