$(formInstructorIdentification + 'name,' + formInstructorIdentification + 'filiation_1,' + formInstructorIdentification + 'filiation_2').on('focusout', function () {
    var id = '#' + $(this).attr("id");

    $(this).val($(this).val().toUpperCase());

    validateNamePerson(this.value, function(ret){
        if (!ret[0]) {
            $(id).attr('value', '');
            addError(id, ret[1]);
        } else {
            removeError(id);
        }
    });


});
$(formInstructorIdentification + 'email').on('focusout', function () {
    var id = '#' + $(this).attr("id");

    $(id).val($(id).val().toUpperCase());

    if (!validateEmail($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Digite um e-mail válido.");
    } else {
        removeError(id);
    }

});

var date = new Date();
$(formInstructorIdentification + 'birthday_date').mask("00/00/0000", {placeholder: "dd/mm/aaaa"});
$(formInstructorIdentification + 'birthday_date').focusout(function () {
    var id = '#' + $(this).attr("id");
    var birthday_date = stringToDate($(formInstructorIdentification + 'birthday_date').val());

    if ((!validateDate($(formInstructorIdentification + 'birthday_date').val()) || !validateYear(birthday_date.year)) && ($(id).val() != '')) {
        addError(id, "Informe uma data válida no formato Dia/Mês/Ano.");
    } else {
        removeError(id);
    }
});

$(formInstructorIdentification + 'nationality').on('change', function () {
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

    if (nationality == 1) {
        uf.removeAttr("disabled").closest(".control-group").find(".control-label").addClass("required").html("Estado *");
        city.removeAttr("disabled").closest(".control-group").find(".control-label").addClass("required").html("Cidade *");
    } else {
        uf.val("").attr("disabled", "disabled").closest(".control-group").find(".control-label").removeClass("required").html("Estado");
        city.val("").attr("disabled", "disabled").closest(".control-group").find(".control-label").removeClass("required").html("Cidade");
    }
});
$(formInstructorIdentification + 'nationality').trigger("change");

$(formInstructorIdentification + 'deficiency').on('change', function () {
    if ($(this).is(':checked')) {
        $("#InstructorIdentification_deficiencies input").removeAttr('disabled');
    } else {
        $("#InstructorIdentification_deficiencies input").removeAttr('checked').add().attr('disabled', 'disabled');
    }
});


$(formInstructorIdentification + 'deficiency_type_blindness').on('click', function () {
    checkMultipleDeficiencies();

    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_low_vision').removeAttr('checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_monocular_vision').removeAttr('checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        $(formInstructorIdentification + 'deficiency_type_low_vision').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_monocular_vision').removeAttr('disabled');
        if (!$(formInstructorIdentification + 'deficiency_type_disability_hearing').is(":checked")) {
            $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('disabled');
            $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
        }
    }
});

$(formInstructorIdentification + 'deficiency_type_low_vision').on('click', function () {
    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        if (!$(formInstructorIdentification + 'deficiency_type_monocular_vision').is(":checked") && !$(formInstructorIdentification + 'deficiency_type_deafness').is(":checked")) {
            $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('disabled');
            if (!$(formInstructorIdentification + 'deficiency_type_disability_hearing').is(":checked")) {
                $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
            }
        }
    }
    checkMultipleDeficiencies();
});

$(formInstructorIdentification + 'deficiency_type_monocular_vision').on('click', function () {
    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        if (!$(formInstructorIdentification + 'deficiency_type_low_vision').is(":checked") && !$(formInstructorIdentification + 'deficiency_type_deafness').is(":checked")) {
            $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('disabled');
            if (!$(formInstructorIdentification + 'deficiency_type_disability_hearing').is(":checked")) {
                $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
            }
        }
    }
    checkMultipleDeficiencies();
});

$(formInstructorIdentification + 'deficiency_type_deafness').on('click', function () {
    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        if (!$(formInstructorIdentification + 'deficiency_type_low_vision').is(":checked") && !$(formInstructorIdentification + 'deficiency_type_monocular_vision').is(":checked")) {
            $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('disabled');
            $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
        }
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').removeAttr('disabled');
    }
    checkMultipleDeficiencies();
});

$(formInstructorIdentification + 'deficiency_type_disability_hearing').on('click', function () {
    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        if (!$(formInstructorIdentification + 'deficiency_type_blindness').is(":checked")) {
            $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('disabled');
            if (!$(formInstructorIdentification + 'deficiency_type_low_vision').is(":checked") && !$(formInstructorIdentification + 'deficiency_type_monocular_vision').is(":checked")) {
                $(formInstructorIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
            }
        }
    }
    checkMultipleDeficiencies();
});

$(formInstructorIdentification + 'deficiency_type_deafblindness').on('click', function () {
    if ($(this).is(':checked')) {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_low_vision').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_monocular_vision').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        $(formInstructorIdentification + 'deficiency_type_blindness').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_low_vision').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_monocular_vision').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_deafness').removeAttr('disabled');
        $(formInstructorIdentification + 'deficiency_type_disability_hearing').removeAttr('disabled');
    }
    checkMultipleDeficiencies();
});

$(formInstructorIdentification + 'deficiency_type_phisical_disability').on('click', function () {
    checkMultipleDeficiencies();
});

$(formInstructorIdentification + 'deficiency_type_intelectual_disability').on('click', function () {
    checkMultipleDeficiencies();
});

function checkMultipleDeficiencies() {
    var length = 0;
    length = $(formInstructorIdentification + 'deficiency_type_blindness').is(':checked') ? length + 1 : length;
    length = $(formInstructorIdentification + 'deficiency_type_low_vision').is(':checked') ? length + 1 : length;
    length = $(formInstructorIdentification + 'deficiency_type_monocular_vision').is(':checked') ? length + 1 : length;
    length = $(formInstructorIdentification + 'deficiency_type_deafness').is(':checked') ? length + 1 : length;
    length = $(formInstructorIdentification + 'deficiency_type_disability_hearing').is(':checked') ? length + 1 : length;
    length = $(formInstructorIdentification + 'deficiency_type_deafblindness').is(':checked') ? length + 1 : length;
    length = $(formInstructorIdentification + 'deficiency_type_phisical_disability').is(':checked') ? length + 1 : length;
    length = $(formInstructorIdentification + 'deficiency_type_intelectual_disability').is(':checked') ? length + 1 : length;

    if (length >= 2) {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').attr('checked', 'checked');
    } else {
        $(formInstructorIdentification + 'deficiency_type_multiple_disabilities').removeAttr('checked', 'checked');
    }
}

var formDocumentsAndAddress = '#InstructorDocumentsAndAddress_';

$(formDocumentsAndAddress + 'cpf').mask("000.000.000-00", {placeholder: "___.___.___-__"});
$(formDocumentsAndAddress + 'cpf').on('change', function () {
    const id = '#' + $(this).attr("id");
    const validationState = validateCpf($(id).val().replace(/\D/g, ''));
    if (!validationState.valid) {
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'cep').focusout(function () {
    var name = $(this).attr("id");
    var id = '#' + name;
    var element = $(id);

    var form = formDocumentsAndAddress.replace('#', '');

    var address = $("label[for=" + form + "address]");
    var neighborhood = $("label[for=" + form + "neighborhood]");
    var edcenso_uf_fk = $("#" + form + "edcenso_uf_fk");
    var edcenso_city_fk = $("#" + form + "edcenso_city_fk");

    var noError = false;
    var required = false;

    element.val(element.val().toUpperCase());

    noError = validateCEP(element.val()) || !(element.val().length > 0);
    required = validateCEP(element.val()) && noError;


    if (noError) {
        removeError(id);
    } else {
        element.attr('value', '');
        addError(id, "Informe um CEP cadastrado nos correios. Apenas números são aceitos. Deve possuir no máximo 8 caracteres.");
    }

    if (required) {
        addRequired(address);
        addRequired(neighborhood);
        addRequiredSelect2(edcenso_uf_fk);
        addRequiredSelect2(edcenso_city_fk);
    } else {
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

$(formDocumentsAndAddress + 'address').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());

    if (!validateInstructorAddress($(id).val())) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'address_number').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());

    if (!validateInstructorAddressNumber($(id).val())) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1.");
    } else {
        removeError(id);
    }
});
$(formDocumentsAndAddress + 'neighborhood').focusout(function () {
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
    #InstructorVariableData_high_education_initial_year_3').on('change', function () {
    if (this.value.length == 4) {
        var data = new Date();
        if (!anoMinMax(2002, data.getFullYear(), this.value)) {
            $(this).attr('value', '');
        } else {
            $(this).attr('value', '');
        }
    }
});
$('#InstructorVariableData_high_education_final_year_1,\n\
    #InstructorVariableData_high_education_final_year_2,\n\
    #InstructorVariableData_high_education_final_year_3').on('change', function () {
    if (this.value.length == 4) {
        var data = new Date();
        if (!anoMinMax(1941, data.getFullYear(), this.value)) {
            $(this).attr('value', '');
        }
    } else {
        $(this).attr('value', '');
    }
});

$(formInstructorvariableData + 'scholarity').on('change', function () {
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
        $(formInstructorvariableData + 'high_education_situation_1').val("").attr('disabled', 'disabled').trigger("change");
        $(formInstructorvariableData + 'high_education_formation_1').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_course_code_1_fk').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_initial_year_1').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_final_year_1').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_institution_code_1_fk').add().attr('disabled', 'disabled');

        $(formInstructorvariableData + 'high_education_situation_2').val("").attr('disabled', 'disabled').trigger("change");
        $(formInstructorvariableData + 'high_education_formation_2').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_course_code_2_fk').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_initial_year_2').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_final_year_2').add().attr('disabled', 'disabled');
        $(formInstructorvariableData + 'high_education_institution_code_2_fk').add().attr('disabled', 'disabled');

        $(formInstructorvariableData + 'high_education_situation_3').val("").attr('disabled', 'disabled').trigger("change");
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

    $(formInstructorvariableData + 'high_education_initial_year_1').mask("0000");
    $(formInstructorvariableData + 'high_education_final_year_1').mask("0000");
    $(formInstructorvariableData + 'high_education_initial_year_2').mask("0000");
    $(formInstructorvariableData + 'high_education_final_year_2').mask("0000");
    $(formInstructorvariableData + 'high_education_initial_year_3').mask("0000");
    $(formInstructorvariableData + 'high_education_final_year_3').mask("0000");

    $(formInstructorvariableData + 'high_education_situation_1').on('change', function () {
        if ($(this).val() == "") {
            $("#tab-instructor-data2").addClass("disabled");
            $(formInstructorvariableData + 'high_education_initial_year_1').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Início do Curso Superior 1");
            $(formInstructorvariableData + 'high_education_final_year_1').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Conclusão do Curso Superior 1");
            $(formInstructorvariableData + 'high_education_formation_1').prop("checked", false).attr('disabled', 'disabled');
            $('#high_education_course_area1').val("").attr("disabled", "disabled").trigger("change.select2");
            $(formInstructorvariableData + 'high_education_course_code_1_fk').val("").attr("disabled", "disabled").trigger("change.select2").closest(".control-group").find(".control-label").removeClass("required").html("Código do Curso Superior 1");
            $(formInstructorvariableData + 'high_education_institution_code_1_fk').closest(".control-group").hide();
        } else {
            $("#tab-instructor-data2").removeClass("disabled");
            $(formInstructorvariableData + 'high_education_formation_1').removeAttr('disabled');
            $('#high_education_course_area1').removeAttr('disabled');
            $(formInstructorvariableData + 'high_education_course_code_1_fk').removeAttr('disabled').closest(".control-group").find(".control-label").addClass("required").html("Código do Curso Superior 1 *");
            $(formInstructorvariableData + 'high_education_institution_code_1_fk').closest(".control-group").show();
            if ($(this).val() == 1) { // Concluído
                $(formInstructorvariableData + 'high_education_initial_year_1').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Início do Curso Superior 1");
                $(formInstructorvariableData + 'high_education_final_year_1').removeAttr('disabled').closest(".control-group").find(".control-label").addClass("required").html("Ano de Conclusão do Curso Superior 1 *");
            } else { // Matriculado
                $(formInstructorvariableData + 'high_education_initial_year_1').removeAttr('disabled').closest(".control-group").find(".control-label").addClass("required").html("Ano de Início do Curso Superior 1 *");
                $(formInstructorvariableData + 'high_education_final_year_1').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Conclusão do Curso Superior 1");
            }
        }
    });
    $(formInstructorvariableData + 'high_education_situation_1').trigger("change");

    $(formInstructorvariableData + 'high_education_situation_2').on('change', function () {
        if ($(this).val() == "") {
            $("#tab-instructor-data3").addClass("disabled");
            $(formInstructorvariableData + 'high_education_initial_year_2').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Início do Curso Superior 2");
            $(formInstructorvariableData + 'high_education_final_year_2').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Conclusão do Curso Superior 2");
            $(formInstructorvariableData + 'high_education_formation_2').prop("checked", false).attr('disabled', 'disabled');
            $('#high_education_course_area2').val("").attr("disabled", "disabled").trigger("change.select2");
            $(formInstructorvariableData + 'high_education_course_code_2_fk').val("").attr("disabled", "disabled").trigger("change.select2").closest(".control-group").find(".control-label").removeClass("required").html("Código do Curso Superior 2");
            $(formInstructorvariableData + 'high_education_institution_code_2_fk').closest(".control-group").hide();
        } else {
            $("#tab-instructor-data3").removeClass("disabled");
            $(formInstructorvariableData + 'high_education_formation_2').removeAttr('disabled');
            $('#high_education_course_area2').removeAttr('disabled');
            $(formInstructorvariableData + 'high_education_course_code_2_fk').removeAttr('disabled').closest(".control-group").find(".control-label").addClass("required").html("Código do Curso Superior 2 *");
            $(formInstructorvariableData + 'high_education_institution_code_2_fk').closest(".control-group").show();
            if ($(this).val() == 1) { // Concluído
                $(formInstructorvariableData + 'high_education_initial_year_2').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Início do Curso Superior 2");
                $(formInstructorvariableData + 'high_education_final_year_2').removeAttr('disabled').closest(".control-group").find(".control-label").addClass("required").html("Ano de Conclusão do Curso Superior 2 *");
            } else { // Matriculado
                $(formInstructorvariableData + 'high_education_initial_year_2').removeAttr('disabled').closest(".control-group").find(".control-label").addClass("required").html("Ano de Início do Curso Superior 2 *");
                $(formInstructorvariableData + 'high_education_final_year_2').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Conclusão do Curso Superior 2");
            }
        }
    });
    $(formInstructorvariableData + 'high_education_situation_2').trigger("change");

    $(formInstructorvariableData + 'high_education_situation_3').on('change', function () {
        if ($(this).val() == "") {
            $(formInstructorvariableData + 'high_education_initial_year_3').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Início do Curso Superior 3");
            $(formInstructorvariableData + 'high_education_final_year_3').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Conclusão do Curso Superior 3");
            $(formInstructorvariableData + 'high_education_formation_3').prop("checked", false).attr('disabled', 'disabled');
            $('#high_education_course_area3').val("").attr("disabled", "disabled").trigger("change.select2");
            $(formInstructorvariableData + 'high_education_course_code_3_fk').val("").attr("disabled", "disabled").trigger("change.select2").closest(".control-group").find(".control-label").removeClass("required").html("Código do Curso Superior 3");
            $(formInstructorvariableData + 'high_education_institution_code_3_fk').closest(".control-group").hide();
        } else {
            $(formInstructorvariableData + 'high_education_formation_3').removeAttr('disabled');
            $('#high_education_course_area3').removeAttr('disabled');
            $(formInstructorvariableData + 'high_education_course_code_3_fk').removeAttr('disabled').closest(".control-group").find(".control-label").addClass("required").html("Código do Curso Superior 3 *");
            $(formInstructorvariableData + 'high_education_institution_code_3_fk').closest(".control-group").show();
            if ($(this).val() == 1) { // Concluído
                $(formInstructorvariableData + 'high_education_initial_year_3').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Início do Curso Superior 3");
                $(formInstructorvariableData + 'high_education_final_year_3').removeAttr('disabled').closest(".control-group").find(".control-label").addClass("required").html("Ano de Conclusão do Curso Superior 3 *");
            } else { // Matriculado
                $(formInstructorvariableData + 'high_education_initial_year_3').removeAttr('disabled').closest(".control-group").find(".control-label").addClass("required").html("Ano de Início do Curso Superior 3 *");
                $(formInstructorvariableData + 'high_education_final_year_3').val("").attr('disabled', 'disabled').closest(".control-group").find(".control-label").removeClass("required").html("Ano de Conclusão do Curso Superior 3");
            }
        }
    });
    $(formInstructorvariableData + 'high_education_situation_3').trigger("change");

    $(formInstructorvariableData + 'high_education_course_code_1_fk').on('change', function () {
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

    $(formInstructorvariableData + 'high_education_course_code_2_fk').on('change', function () {
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

    $(formInstructorvariableData + 'high_education_course_code_3_fk').on('change', function () {
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

    $(pgs + ',' + pgm + ',' + pgd).on('change', function () {
        var checked = !($(pgs).is(':checked') || $(pgm).is(':checked') || $(pgd).is(':checked'));
        $(pgn).attr('checked', checked);
    });
    $(pgn).on('change', function () {
        if ($(pgn).is(':checked')) {
            $(pgs + ',' + pgm + ',' + pgd).attr('checked', false);
        }
    });

    var ocn = formInstructorvariableData + 'other_courses_none';

    $('.other_courses').on('change', function () {
        var checked = $('.other_courses').is(':checked');
        $(ocn).attr('checked', checked);
    });
    $(ocn).on('change', function () {
        if ($(ocn).is(':checked')) {
            $('.other_courses').attr('checked', false);
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
    if ($("#InstructorDocumentsAndAddress_cpf").val() === "") {
        error = true;
        message += "Campo <b>Nº do CPF</b> é obrigatório.<br>";
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

    if ($("#InstructorDocumentsAndAddress_cpf_error").length) {
        error = true;
        message += "Corrija o campo <b>Nº do CPF</b>.<br>";
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
    if ($("#InstructorIdentification_nationality").val() === "1" && $("#InstructorIdentification_edcenso_uf_fk").val() === "") {
        error = true;
        message += "Campo <b>Estado</b> é obrigatório.<br>";
    }
    if ($("#InstructorIdentification_nationality").val() === "1" && $("#InstructorIdentification_edcenso_city_fk").val() === "") {
        error = true;
        message += "Campo <b>Cidade</b> é obrigatório.<br>";
    }
    if ($("#InstructorIdentification_deficiency").is(":checked") && !$(".deficiencies-container input[type=checkbox]:checked").length) {
        error = true;
        message += "Como o campo <b>Deficiência</b> foi preenchido, deve-se preencher pelo menos um <b>Tipo de deficiência</b>.<br>";
    }
    if ($("#InstructorDocumentsAndAddress_area_of_residence").val() === "") {
        error = true;
        message += "Campo <b>Localização / Zona de residência</b> é obrigatório.<br>";
    }
    if ($("#InstructorDocumentsAndAddress_area_of_residence").val() === "1" && $("#InstructorDocumentsAndAddress_diff_location").val() === "1") {
        error = true;
        message += "Quando o campo <b>Localização / Zona de residência</b> é selecionado 'Urbano', o campo <b>Localização Diferenciada</b> não pode ser uma área de assentamento.<br>";
    }
    if ($("#InstructorIdentification_filiation").val() === '1' && ($("#InstructorIdentification_filiation_1").val() === "" && $("#InstructorIdentification_filiation_2").val() === "")) {
        error = true;
        message += "Quando o campo <b>Filiação</b> é selecionado como 'Declarado', pelo menos um dos campos <b>Nome Completo da Filiação</b> ou <b>Nome Completo do Pai</b> devem ser preenchidos.<br>";
    }
    var variableData1Filled = false;
    if (($("#InstructorVariableData_high_education_situation_1").val() === "1" && ($("#InstructorVariableData_high_education_course_code_1_fk").val() === "" || $("#InstructorVariableData_high_education_final_year_1").val() === "" || $("#InstructorVariableData_high_education_institution_code_1_fk").val() === ""))
        || $("#InstructorVariableData_high_education_situation_1").val() === "2" && ($("#InstructorVariableData_high_education_course_code_1_fk").val() === "" || $("#InstructorVariableData_high_education_initial_year_1").val() === "" || $("#InstructorVariableData_high_education_institution_code_1_fk").val() === "")) {
        error = true;
        message += "Preencha os campos obrigatórios do <b>Curso 1</b>.<br>";
    } else if ($("#InstructorVariableData_high_education_situation_1").val() !== "") {
        variableData1Filled = true;
    }
    if (($("#InstructorVariableData_high_education_situation_2").val() === "1" && ($("#InstructorVariableData_high_education_course_code_2_fk").val() === "" || $("#InstructorVariableData_high_education_final_year_2").val() === "" || $("#InstructorVariableData_high_education_institution_code_2_fk").val() === ""))
        || $("#InstructorVariableData_high_education_situation_2").val() === "2" && ($("#InstructorVariableData_high_education_course_code_2_fk").val() === "" || $("#InstructorVariableData_high_education_initial_year_2").val() === "" || $("#InstructorVariableData_high_education_institution_code_2_fk").val() === "")) {
        error = true;
        message += "Preencha os campos obrigatórios do <b>Curso 2</b>.<br>";
    }
    if (($("#InstructorVariableData_high_education_situation_3").val() === "1" && ($("#InstructorVariableData_high_education_course_code_3_fk").val() === "" || $("#InstructorVariableData_high_education_final_year_3").val() === "" || $("#InstructorVariableData_high_education_institution_code_3_fk").val() === ""))
        || $("#InstructorVariableData_high_education_situation_3").val() === "2" && ($("#InstructorVariableData_high_education_course_code_3_fk").val() === "" || $("#InstructorVariableData_high_education_initial_year_3").val() === "" || $("#InstructorVariableData_high_education_institution_code_3_fk").val() === "")) {
        error = true;
        message += "Preencha os campos obrigatórios do <b>Curso 3</b>.<br>";
    }
    if ($("#InstructorVariableData_scholarity").val() === '6' && !variableData1Filled) {
        error = true;
        message += "Quando o campo <b>Escolaridade</b> é selecionado como 'Superior', deve-se preencher pelo menos o <b>Curso 1</b>.<br>";
    }
    if (error) {
        $("html, body").animate({scrollTop: 0}, "fast");
        $(this).closest("form").find(".instructor-error").html(message).show();
    } else {
        $(this).closest("form").find(".instructor-error").hide();
        $(formDocumentsAndAddress + "cpf").unmask();
        $("#instructor-form input").removeAttr("disabled");
        $("#instructor-form select").removeAttr("disabled").trigger("change.select2");
        $(this).closest("form").submit();
    }
});
