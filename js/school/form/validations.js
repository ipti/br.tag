/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(formIdentification + 'initial_date').mask("99/99/9999");
$(formIdentification + 'initial_date').focusout(function () {
    var id = '#' + $(this).attr("id");
    initial_date = stringToDate($(formIdentification + 'initial_date').val());
    if (!validateDate($(formIdentification + 'initial_date').val())
        || !(initial_date.year >= actual_year - 1
            && initial_date.year <= actual_year)) {
        $(formIdentification + 'initial_date').attr('value', '');
        addError(id, "A data deve ser válida, no formato Dia/Mês/Ano e inferior a data final.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'final_date').mask("99/99/9999");
$(formIdentification + 'final_date').focusout(function () {
    var id = '#' + $(this).attr("id");
    final_date = stringToDate($(formIdentification + 'final_date').val());
    if (!validateDate($(formIdentification + 'final_date').val())
        || !(final_date.year >= actual_year
            && final_date.year <= actual_year + 1)
        || !(final_date.asianStr > initial_date.asianStr)) {
        $(formIdentification + 'final_date').attr('value', '');
        addError(id, "A data deve ser válida, no formato Dia/Mês/Ano e superior a data inicial.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'name').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateSchoolName($(id).val())) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 4.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'cep, ' + formIdentification + 'inep_id').focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validateCEP($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Informe um CEP cadastrado nos correios. Apenas números são aceitos. Deve possuir no máximo 8 caracteres.");
    } else {
        removeError(id);
    }
});


$(formIdentification + 'ddd').focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validateDDD($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Apenas números são aceitos. Deve possuir 2 caracteres.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'address').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateAddress($(id).val(), 100)) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -.");
    } else {
        removeError(id);
    }
});
$(formIdentification + 'address_number').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateAddress($(id).val(), 10)) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -.");
    } else {
        removeError(id);
    }
});
/*$(formIdentification+'address_complement').focusout(function() { 
 var id = '#'+$(this).attr("id");
 $(id).val($(id).val().toUpperCase());
 if(!validateAddress($(id).val(),20)) {
 $(id).attr('value','');
 addError(id, "Campo não está dentro das regras.");
 }else{
 removeError(id);
 }
 });*/
$(formIdentification + 'address_neighborhood').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateAddress($(id).val(), 50)) {
        $(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'phone_number').focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validatePhone($(id).val(), 9)) {
        $(id).attr('value', '');
        addError(id, "Apenas números são aceitos. Não pode ter todos os algarismos iguais. Deve ter 8 ou 9 números. Se houver 9 números, o primeiro algarismo deve ser o dígito 9.");
    } else {
        removeError(id);
    }
});
$(formIdentification + 'public_phone_number').focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validatePhone($(id).val(), 8)) {
        $(id).attr('value', '');
        addError(id, "Apenas números são aceitos. Não pode ter todos os algarismos iguais. Deve ter 8 ou 9 números. Se houver 9 números, o primeiro algarismo deve ser o dígito 9.");
    } else {
        removeError(id);
    }
});
$(formIdentification + 'other_phone_number').focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validatePhone($(id).val(), 9)) {
        $(id).attr('value', '');
        addError(id, "Apenas números são aceitos. Não pode ter todos os algarismos iguais. Deve ter 8 ou 9 números. Se houver 9 números, o primeiro algarismo deve ser o dígito 9.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'email').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateEmail($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Digite um e-mail válido.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'manager_cpf').focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validateCpf($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'manager_name').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    var ret = validateNamePerson(($(id).val()));
    if (!ret[0]) {
        $(id).attr('value', '');
        addError(id, ret[1]);
    } else {
        removeError(id);
    }
});

$(formIdentification + 'manager_email').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateEmail($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Digite um e-mail válido.");
    } else {
        removeError(id);
    }
});

$(formStructure + 'used_classroom_count, '
    + formStructure + 'classroom_count, '
    + formStructure + 'equipments_tv , '
    + formStructure + 'equipments_vcr , '
    + formStructure + 'equipments_dvd , '
    + formStructure + 'equipments_satellite_dish , '
    + formStructure + 'equipments_copier , '
    + formStructure + 'equipments_overhead_projector , '
    + formStructure + 'equipments_printer , '
    + formStructure + 'equipments_stereo_system , '
    + formStructure + 'equipments_data_show , '
    + formStructure + 'equipments_fax , '
    + formStructure + 'equipments_camera , '
    + formStructure + 'equipments_computer, '
    + formStructure + 'administrative_computers_count, '
    + formStructure + 'student_computers_count, '
    + formStructure + 'employees_count '
).focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validateCount($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Não pode ser preenchido com zero. Caso a escola não tenha o equipamento, o campo deve vir vazio");
    } else {
        removeError(id);
    }
});

$(formStructure + 'operation_location input[type=checkbox]').change(function () {
    var id = '#' + $(formStructure + 'operation_location').attr("id");
    if ($('#SchoolStructure_operation_location input[type=checkbox]:checked').length == 0) {
        addError(id, "Informe ao menos um local de funcionamento.");
    } else {
        removeError(id);
    }
});

$(formStructure + 'operation_location').focusout(function () {
    var id = '#' + $(this).attr("id");
    if ($('#SchoolStructure_operation_location input[type=checkbox]:checked').length == 0) {
        addError(id, "Informe ao menos um local de funcionamento.");
    } else {
        removeError(id);
    }
});

$(formStructure + 'native_education').change(function () {
    if ($(formStructure + 'native_education:checked').length == 1) {
        $("#native_education_language").show();
        $("#native_education_lenguage_none input").attr('disabled', 'disabled');
        $("#native_education_lenguage_some input").removeAttr('disabled');
    } else {
        $("#native_education_language").hide();
        $("#native_education_lenguage_none input").val(null).removeAttr('disabled');
        $("#native_education_lenguage_some input").attr('disabled', 'disabled');
    }
});

$(formStructure + 'native_education').trigger('change');

$(".save-school-button").click(function () {
    var error = false;
    var message = "";
    if ($("#SchoolIdentification_name").val() === "") {
        error = true;
        message += "Campo <b>Nome</b> é obrigatório.<br>";
    }
    if ($("input#SchoolIdentification_inep_id").val() === "") {
        error = true;
        message += "Campo <b>Código do Inep</b> é obrigatório.<br>";
    }
    if ($("#SchoolIdentification_administrative_dependence").val() === "") {
        error = true;
        message += "Campo <b>Dependencia de Administrativa</b> é obrigatório.<br>";
    }
    if ($("#SchoolIdentification_edcenso_uf_fk").val() === "") {
        error = true;
        message += "Campo <b>Estado</b> é obrigatório.<br>";
    }
    if ($("#SchoolIdentification_edcenso_city_fk").val() === "") {
        error = true;
        message += "Campo <b>Cidade</b> é obrigatório.<br>";
    }
    if ($("#SchoolIdentification_edcenso_district_fk").val() === "") {
        error = true;
        message += "Campo <b>Distrito</b> é obrigatório.<br>";
    }
    if ($("#SchoolIdentification_location").val() === "") {
        error = true;
        message += "Campo <b>Localização</b> é obrigatório.<br>";
    }
    if ($("#SchoolIdentification_offer_or_linked_unity").val() === "") {
        error = true;
        message += "Campo <b>Unidade vinculada de Educação Básica ou ofertante de Ensino Superior</b> é obrigatório.<br>";
    }
    if (!$("#SchoolStructure_operation_location input[type=checkbox]:checked").length) {
        error = true;
        message += "Campo <b>Local de Funcionamento</b> é obrigatório. Selecione ao menos uma opção.";
    }
    if (error) {
        $(this).closest("form").find(".school-error").html(message).show();
    } else {
        $(this).closest("form").find(".school-error").hide();
        $(this).closest("form").submit();
    }
});