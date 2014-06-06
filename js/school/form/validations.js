/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(formIdentification + 'initial_date').mask("99/99/9999");
$(formIdentification + 'initial_date').focusout(function() {
    var id = '#' + $(this).attr("id");
    initial_date = stringToDate($(formIdentification + 'initial_date').val());
    if (!validateDate($(formIdentification + 'initial_date').val())
            || !(initial_date.year >= actual_year - 1
                    && initial_date.year <= actual_year)) {
        $(formIdentification + 'initial_date').attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'final_date').mask("99/99/9999");
$(formIdentification + 'final_date').focusout(function() {
    var id = '#' + $(this).attr("id");
    final_date = stringToDate($(formIdentification + 'final_date').val());
    if (!validateDate($(formIdentification + 'final_date').val())
            || !(final_date.year >= actual_year
                    && final_date.year <= actual_year + 1)
            || !(final_date.asianStr > initial_date.asianStr)) {
        $(formIdentification + 'final_date').attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'name').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateSchoolName($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'cep, ' + formIdentification + 'inep_id').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validateCEP($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});


$(formIdentification + 'ddd').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validateDDD($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'address').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateAddress($(id).val(), 100)) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});
$(formIdentification + 'address_number').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateAddress($(id).val(), 10)) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
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
$(formIdentification + 'address_neighborhood').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateAddress($(id).val(), 50)) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'phone_number').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validatePhone($(id).val(), 9)) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});
$(formIdentification + 'public_phone_number').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validatePhone($(id).val(), 8)) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});
$(formIdentification + 'other_phone_number').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validatePhone($(id).val(), 9)) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'email').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateEmail($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formStructure + 'manager_cpf').focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validateCpf($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formStructure + 'manager_name').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    var ret = validateNamePerson(($(id).val()));
    if (!ret[0]) {
        $(id).attr('value', '');
        addError(id, "Campo Endereço não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formStructure + 'manager_email').focusout(function() {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateEmail($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
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
        ).focusout(function() {
    var id = '#' + $(this).attr("id");
    if (!validateCount($(id).val())) {
        $(id).attr('value', '');
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formStructure + 'operation_location input[type=checkbox]').change(function() {
    var id = '#' + $(formStructure + 'operation_location').attr("id");
    if ($('#SchoolStructure_operation_location input[type=checkbox]:checked').length == 0) {
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formStructure + 'operation_location').focusout(function() {
    var id = '#' + $(this).attr("id");
    if ($('#SchoolStructure_operation_location input[type=checkbox]:checked').length == 0) {
        addError(id, "Campo não está dentro das regras.");
    } else {
        removeError(id);
    }
});

$(formStructure+'native_education').change(function(){
    if($(formStructure+'native_education:checked').length == 1){
        $("#native_education_language").show();
        $("#native_education_lenguage_none input").attr('disabled', 'disabled');
        $("#native_education_lenguage_some input").removeAttr('disabled');
    }else{
        $("#native_education_language").hide();
        $("#native_education_lenguage_none input").val(null).removeAttr('disabled');
        $("#native_education_lenguage_some input").attr('disabled', 'disabled');
    }
});

$(formStructure+'native_education').trigger('change');