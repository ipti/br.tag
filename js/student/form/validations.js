$(formIdentification + 'name').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    var id_error_icon = $("#errorNameIcon");
    var id_warn_icon = $("#warningNameIcon");
    var id_caixa = $("#similarMessage");
    $(id_warn_icon).css('cursor', 'auto');
    removeWarning(id, id_caixa, id_warn_icon);
    removeError(id, id_caixa, id_error_icon);
    validateNamePerson(($(id).val()), function (ret) {
        if (!ret[0] && ($(id).val() != '')) {
            removeWarning(id, id_caixa, id_warn_icon)
            if(ret[2] != null) {
                warningMessage(id, ret[2]);
                $(id_warn_icon).css('cursor', 'pointer');
                addWarning(id, ret[1], id_caixa, id_warn_icon);
            }else {
                addError(id);
                $(id_error_icon).css('display', 'inline-block');
                $(id_caixa).attr('data-original-title', ret[1]);
            }
        } else {
            removeError(id, id_caixa, id_error_icon);
            if(ret[0] && ($(id).val() != '') && ret[1] != null) {
                if(ret[2] != null) {
                    warningMessage(id, ret[2]);
                    $(id_warn_icon).css('cursor', 'pointer');
                    addWarning(id, ret[1], id_caixa, id_warn_icon);
                }else {
                    addError(id);
                    $(id_error_icon).css('display', 'inline-block');
                    $(id_caixa).attr('data-original-title', ret[1]);
                }
            }else {
                removeWarning(id, id_caixa, id_warn_icon);
            }
        }
    });
});

$(formIdentification + 'nis').focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validateNis($(id).val()) && ($(id).val() != '')) {
        //$(id).attr('value', '');
        addError(id, "Apenas números são aceitos. Deve possuir 11 números");
    } else {
        removeError(id);
    }
});


var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
};
var spOptions = {
    onKeyPress: function (val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
    },
    placeholder: "(__) _____-____"
};

$(formIdentification + 'responsable_telephone').mask(SPMaskBehavior, spOptions);
$(formIdentification + 'responsable_telephone').focusout(function () {
    var id = '#' + $(this).attr("id");
    var phone = $(id).cleanVal();
    if (phone.length !== 11 && phone.length !== 10) {
        //$(id).attr('value', '');
        addError(id, "Apenas números são aceitos. Deve possuir de 10 a 11 números.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'responsable_cpf').mask("000.000.000-00", {placeholder: "___.___.___-__"});
$(formIdentification + 'responsable_cpf').focusout(function () {
    var id = '#' + $(this).attr("id");
    removeError(id);
    const validationState = validateCpf($(id).cleanVal());
    if (!validationState.valid) {
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'civil_certification_term_number').focusout(function () {
    var id = '#' + $(this).attr("id");
    var id_caixa = $("#termMessage");
    var id_icon = $("#errorTermIcon");
    removeError(id);
    $(id_icon).css('display', 'none');
    $(id_caixa).attr('data-original-title', '');
    validateCivilCertificationTermNumber($(id).val(), function (ret) {
        if (!ret[0] && ($(id).val() != '')) {
            addError(id);
            $(id_icon).css('display', 'inline-block');
            $(id_caixa).attr('data-original-title', ret[1]);
        } else {
            removeError(id);
            $(id_icon).css('display', 'none');
            $(id_caixa).attr('data-original-title', '');
        }
    });
});

$(formIdentification + 'filiation_1_cpf').mask("000.000.000-00", {placeholder: "___.___.___-__"});
$(formIdentification + 'filiation_1_cpf').focusout(function () {
    var id = '#' + $(this).attr("id");
    const validationState = validateCpf($(id).cleanVal());
    if (!validationState.valid) {
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'filiation_2_cpf').mask("000.000.000-00", {placeholder: "___.___.___-__"});
$(formIdentification + 'filiation_2_cpf').focusout(function () {
    var id = '#' + $(this).attr("id");
    const validationState = validateCpf($(id).cleanVal());
    if (!validationState.valid) {
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'cns').focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validateCns($(id).val())) {
        //$(id).attr('value', '');
        addError(id, "Apenas números são aceitos. Deve possuir 15 números");
    } else {
        removeError(id);
    }
});

var date = new Date();
$(formIdentification + 'birthday').mask("00/00/0000", {placeholder: "dd/mm/aaaa"});
$(formIdentification + 'birthday').focusout(function () {
    var id = '#' + $(this).attr("id");
    var birthday = stringToDate($(formIdentification + 'birthday').val());

-visibility-fname
    if ((!validateDate($(formIdentification + 'birthday').val()) || !validateYear(birthday.year)) && ($(id).val() != '')) {
        //$(formIdentification + 'birthday').attr('value', '');
        addError(id, "Informe uma data válida no formato Dia/Mês/Ano.");
    } else {
        removeError(id);
    }
});

$(formIdentification + 'filiation').change(function () {
    var simple = getUrlVars()['simple'];
    $('.js-disabled-finputs').hide();
    if ($(formIdentification + 'filiation').val() == 1) {
        $('.js-disabled-finputs').show();
        $(formIdentification + 'filiation_1').closest(".js-visibility-fname").show();
        $(formIdentification + 'filiation_2').closest(".js-visibility-fname").show();
    } else {
        $('.js-finput-clear').val("")
        $(formIdentification + 'filiation_1').closest(".js-visibility-fname").css("display", simple === "1" ? "none" : "block");
        $(formIdentification + 'filiation_2').closest(".js-visibility-fname").css("display", simple === "1" ? "none" : "block");

    }
});
$(formIdentification + 'filiation').trigger('change');

$(formIdentification + 'filiation_1').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    removeError(id);
    validateNamePerson(($(id).val()), function (ret) {
        if (!ret[0]) {
            $(id).attr('value', '');
            addError(id, ret[1]);
        } else {
            removeError(id);
            if ($(formIdentification + 'filiation_1').val() !== "" && $(formIdentification + 'filiation_2').val() !== ""
                && $(formIdentification + 'filiation_1').val() === $(formIdentification + 'filiation_2').val()) {
                $(formIdentification + 'filiation_1').attr('value', '');
                addError(id, "O campo não deve ser igual à outra filiação.");
            } else {
                removeError(id);
            }
        } 
    });
}); 

$(formIdentification + 'filiation_2').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    removeError(id);
    validateNamePerson(($(id).val()), function (ret) {
        if (!ret[0]) {
            $(id).attr('value', '');
            addError(id, ret[1]);
        } else {
            removeError(id);
            if ($(formIdentification + 'filiation_1').val() !== "" && $(formIdentification + 'filiation_2').val() !== ""
                && $(formIdentification + 'filiation_1').val() === $(formIdentification + 'filiation_2').val()) {
                $(formIdentification + 'filiation_2').attr('value', '');
                addError(id, "O campo não deve ser igual à outra filiação.");
            } else {
                removeError(id);
            }
        } 
    });
});

$(formIdentification + 'nationality').change(function () {
    var nationality = ".nationality-sensitive";
    var br = nationality + ".br";
    var nobr = nationality + ".no-br";
    var simple = getUrlVars()['simple'];
    $(nationality).attr("disabled", "disabled");
    console.log($(this).val())
    if ($(this).val() == 3) {
        $(nobr).removeAttr("disabled");
        $(formIdentification + 'edcenso_nation_fk').val(null).trigger('change').select2('readonly', false);
        $(formIdentification + 'edcenso_uf_fk').val("").trigger("change.select2");
        $(formIdentification + 'edcenso_city_fk').val("").trigger("change.select2");
        $(formIdentification + 'edcenso_uf_fk').closest(".js-change-required").css("display", simple === "1" ? "none" : "block").find("label").removeClass("required").html("Estado");
        $(formIdentification + 'edcenso_city_fk').closest(".js-change-required").css("display", simple === "1" ? "none" : "block").find("label").removeClass("required").html("Cidade");
    } else if ($(this).val() == "") {
        $(formIdentification + 'edcenso_nation_fk').val(null).trigger('change').attr("disabled", "disabled");
        $(nationality).attr("disabled", "disabled");
        $(formIdentification + 'edcenso_uf_fk').val("").trigger("change.select2").closest(".js-change-required").css("display", simple == "1" ? "none" : "block").find("label").removeClass("required").html("Estado");
        $(formIdentification + 'edcenso_city_fk').val("").trigger("change.select2").closest(".js-change-required").css("display", simple == "1" ? "none" : "block").find("label").removeClass("required").html("Cidade");
    } else {
        $(formIdentification + 'edcenso_nation_fk').val(76).trigger('change').removeAttr("disabled").select2('readonly', true);
        $(br).removeAttr("disabled");
        $(formDocumentsAndAddress + 'civil_certification').trigger("change");
        if ($(this).val() == "1") {
            $(formIdentification + 'edcenso_uf_fk').removeAttr("disabled").closest(".js-change-required").show().find("label").addClass("required").html("Estado *");
            $(formIdentification + 'edcenso_city_fk').removeAttr("disabled").closest(".js-change-required").show().find("label").addClass("required").html("Cidade *");
        } else {
            $(formIdentification + 'edcenso_uf_fk').val("").trigger("change.select2").attr("disabled", "disabled").closest(".js-change-required").css("display", simple == "1" ? "none" : "block").find("label").removeClass("required").html("Estado");
            $(formIdentification + 'edcenso_city_fk').val("").trigger("change.select2").attr("disabled", "disabled").closest(".js-change-required").css("display", simple == "1" ? "none" : "block").find("label").removeClass("required").html("Cidade");
        }
    }
});
$(formIdentification + 'nationality').trigger("change");

var deficiency = formIdentification + "deficiency";                             //17

var dtBlind = formIdentification + 'deficiency_type_blindness';                 //18
var dtLowVi = formIdentification + 'deficiency_type_low_vision';                //19
var dtMonVi = formIdentification + 'deficiency_type_monocular_vision';          //20
var dtDeafn = formIdentification + 'deficiency_type_deafness';                  //20
var dtDisab = formIdentification + 'deficiency_type_disability_hearing';        //21
var dtDeafB = formIdentification + 'deficiency_type_deafblindness';             //22
var dtPhisi = formIdentification + 'deficiency_type_phisical_disability';       //23
var dtIntel = formIdentification + 'deficiency_type_intelectual_disability';    //24
var dtMulti = formIdentification + 'deficiency_type_multiple_disabilities';     //25
var dtAutis = formIdentification + 'deficiency_type_autism';                    //26
var dtAspen = formIdentification + 'deficiency_type_aspenger_syndrome';         //27
var dtRettS = formIdentification + 'deficiency_type_rett_syndrome';             //28
var dtChild = formIdentification + 'deficiency_type_childhood_disintegrative_disorder'; //29
var dtGifte = formIdentification + 'deficiency_type_gifted';                    //30
var allDeficiency = dtBlind + ',' + dtLowVi + ',' + dtMonVi + "," + dtDeafn + ',' + dtDisab + ',' + dtDeafB + ',' + dtGifte + ',' + dtPhisi + ',' +
    dtIntel + ',' + dtMulti + ',' + dtAutis + ',' + dtAspen + ',' + dtRettS + ',' + dtChild;
var defLessGifited = dtBlind + ',' + dtLowVi + ',' + dtDeafn + ',' + dtDisab + ',' + dtDeafB + ',' + dtPhisi + ',' +
    dtIntel + ',' + dtMulti + ',' + dtAutis + ',' + dtAspen + ',' + dtRettS + ',' + dtChild;

var rAidLec = formIdentification + 'resource_aid_lector';                       //31
var rAidTra = formIdentification + 'resource_aid_transcription';                //32
var rIntGui = formIdentification + 'resource_interpreter_guide';                //33
var rIntLib = formIdentification + 'resource_interpreter_libras';               //34
var rLipRea = formIdentification + 'resource_lip_reading';                      //35
var rZoom18 = formIdentification + 'resource_zoomed_test_18';                   //36
var rZoom24 = formIdentification + 'resource_zoomed_test_24';                   //38
var rBraile = formIdentification + 'resource_braille_test';                     //39
var rProLan = formIdentification + 'resource_proof_language';                     //39
var rCdAudi = formIdentification + 'resource_cd_audio';                     //39
var rVidLib = formIdentification + 'resource_video_libras';                     //39
var allResource = rAidLec + ',' + rAidTra + ',' + rIntGui + ',' + rIntLib + ',' + rLipRea + ',' + rZoom18 + ',' + rZoom24 + ',' + rBraile + ',' + rProLan + "," + rCdAudi + "," + rVidLib;

var rNone = formIdentification + 'resource_none';                               //40

var defReadOnly;
var recReadOnly;

$(allDeficiency).click(function () {
    defReadOnly = $(this).attr('readonly') == 'readonly';
});
$(allResource).click(function () {
    recReadOnly = $(this).attr('readonly') == 'readonly';
});


$(formIdentification + 'deficiency_type_blindness').on('click', function () {
    if ($(this).is(':checked')) {
        $(formIdentification + 'deficiency_type_low_vision').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_monocular_vision').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_deafness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        $(formIdentification + 'deficiency_type_low_vision').removeAttr('disabled');
        $(formIdentification + 'deficiency_type_monocular_vision').removeAttr('disabled');
        if (!$(formIdentification + 'deficiency_type_disability_hearing').is(":checked")) {
            $(formIdentification + 'deficiency_type_deafness').removeAttr('disabled');
            $(formIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
        }
    }
});

$(formIdentification + 'deficiency_type_low_vision').on('click', function () {
    if ($(this).is(':checked')) {
        $(formIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        if (!$(formIdentification + 'deficiency_type_monocular_vision').is(":checked") && !$(formIdentification + 'deficiency_type_deafness').is(":checked")) {
            $(formIdentification + 'deficiency_type_blindness').removeAttr('disabled');
            if (!$(formIdentification + 'deficiency_type_disability_hearing').is(":checked")) {
                $(formIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
            }
        }
    }
});

$(formIdentification + 'deficiency_type_monocular_vision').on('click', function () {
    if ($(this).is(':checked')) {
        $(formIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        if (!$(formIdentification + 'deficiency_type_low_vision').is(":checked") && !$(formIdentification + 'deficiency_type_deafness').is(":checked")) {
            $(formIdentification + 'deficiency_type_blindness').removeAttr('disabled');
            if (!$(formIdentification + 'deficiency_type_disability_hearing').is(":checked")) {
                $(formIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
            }
        }
    }
});

$(formIdentification + 'deficiency_type_deafness').on('click', function () {
    if ($(this).is(':checked')) {
        $(formIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_disability_hearing').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        if (!$(formIdentification + 'deficiency_type_low_vision').is(":checked") && !$(formIdentification + 'deficiency_type_monocular_vision').is(":checked")) {
            $(formIdentification + 'deficiency_type_blindness').removeAttr('disabled');
            $(formIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
        }
        $(formIdentification + 'deficiency_type_disability_hearing').removeAttr('disabled');
    }
});

$(formIdentification + 'deficiency_type_disability_hearing').on('click', function () {
    if ($(this).is(':checked')) {
        $(formIdentification + 'deficiency_type_deafness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_deafblindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        if (!$(formIdentification + 'deficiency_type_blindness').is(":checked")) {
            $(formIdentification + 'deficiency_type_deafness').removeAttr('disabled');
            if (!$(formIdentification + 'deficiency_type_low_vision').is(":checked") && !$(formIdentification + 'deficiency_type_monocular_vision').is(":checked")) {
                $(formIdentification + 'deficiency_type_deafblindness').removeAttr('disabled');
            }
        }
    }
});
$(formIdentification + 'deficiency_type_deafblindness').on('click', function () {
    if ($(this).is(':checked')) {
        $(formIdentification + 'deficiency_type_blindness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_low_vision').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_monocular_vision').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_deafness').removeAttr('checked', 'checked').attr('disabled', 'disabled');
        $(formIdentification + 'deficiency_type_disability_hearing').removeAttr('checked', 'checked').attr('disabled', 'disabled');
    } else {
        $(formIdentification + 'deficiency_type_blindness').removeAttr('disabled');
        $(formIdentification + 'deficiency_type_low_vision').removeAttr('disabled');
        $(formIdentification + 'deficiency_type_monocular_vision').removeAttr('disabled');
        $(formIdentification + 'deficiency_type_deafness').removeAttr('disabled');
        $(formIdentification + 'deficiency_type_disability_hearing').removeAttr('disabled');
    }
});

$(formIdentification + 'deficiency_type_intelectual_disability').on('click', function () {
    if ($(this).is(':checked')) {
        $(formIdentification + 'deficiency_type_gifted').removeAttr('checked', 'checked');
        $(formIdentification + 'deficiency_type_gifted').add().attr('disabled', 'disabled');
    } else {
        $(formIdentification + 'deficiency_type_gifted').add().removeAttr('disabled', 'disabled');
    }
});


$(formIdentification + 'deficiency_type_gifted').on('click', function () {
    if ($(this).is(':checked')) {
        $(formIdentification + 'deficiency_type_intelectual_disability').removeAttr('checked', 'checked');
        $(formIdentification + 'deficiency_type_intelectual_disability').add().attr('disabled', 'disabled');

    } else {
        $(formIdentification + 'deficiency_type_intelectual_disability').add().removeAttr('disabled', 'disabled');
    }
});

$('#resource_type input').change(function () {
    if (recReadOnly) {
        $(this).attr('checked', false);
    }

    var checked = !($(this).is(":checked"));
    $('#resource_type ' + rNone).attr('checked', checked);

    var rz16 = $('#resource_type ' + rZoom16);
    var rz20 = $('#resource_type ' + rZoom20);
    var rz24 = $('#resource_type ' + rZoom24);
    rz16.attr('readonly', false);
    rz20.attr('readonly', false);
    rz24.attr('readonly', false);

    if (rz16.is(':checked')) {
        $(rz20).attr('checked', false).attr('readonly', true);
        $(rz24).attr('checked', false).attr('readonly', true);
    }
    if (rz20.is(':checked')) {
        $(rz16).attr('checked', false).attr('readonly', true);
        $(rz24).attr('checked', false).attr('readonly', true);
    }
    if (rz24.is(':checked')) {
        $(rz16).attr('checked', false).attr('readonly', true);
        $(rz20).attr('checked', false).attr('readonly', true);
    }
});

$('#resource_type ' + rNone).change(function () {
    if (recReadOnly) {
        $(this).attr('checked', false);
    }

    if ($(this).is(':checked')) {
        $(allResource).attr('checked', false);
    }
});

$(document).on("change", ".linked-deficiency", function (evt, indirect) {
    if (indirect === undefined) {
        if ($('.linked-deficiency:checked').length > 1) {
            $(formIdentification + "deficiency_type_multiple_disabilities").prop('checked', "checked");
        } else {
            $(formIdentification + "deficiency_type_multiple_disabilities").removeAttr('checked');
        }
    }
});

$(deficiency).change(function () {
    if ($(this).is(":checked")) {
        $(allDeficiency).removeAttr('disabled');
        $("#StudentIdentification_deficiencies").parent(".js-change-required").show().find("label").addClass("required");
        $("#StudentIdentification_deficiency_type_blindness").trigger("change", [true]);
        $(".resources-container").show();
    } else {
        $(allDeficiency).attr('disabled', "disabled").removeAttr('checked');
        $("#StudentIdentification_deficiencies").parent(".js-visibility-deficiencies").hide();
        $("#StudentIdentification_resource_aid_lector").closest(".js-visibility-dresource").hide();
        $(".resources-container").hide();
        $(".resources-container input[type=checkbox]").prop("checked", false);
    }
});
$(deficiency).trigger('change');

$(".deficiencies-container input[type=checkbox]").change(function () {
    if ($(".deficiencies-container input[type=checkbox]").not("#StudentIdentification_deficiency_type_gifted").is(":checked")) {
        $(".resources-container").find(".control-label").addClass("required").html("Recursos requeridos em avaliações do INEP (Prova Brasil, SAEB, outros) *");
    } else {
        $(".resources-container").find(".control-label").removeClass("required").html("Recursos requeridos em avaliações do INEP (Prova Brasil, SAEB, outros)");
    }
});
$(".deficiencies-container input[type=checkbox]").trigger('change', [true]);

$(formDocumentsAndAddress + 'address').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddress($(id).val())) {
        //$(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'number').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddressNumber($(id).val())) {
        //$(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'complement').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddressComplement($(id).val())) {
        //$(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'neighborhood').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddressNeighborhood($(id).val())) {
        //$(id).attr('value', '');
        addError(id, "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1.");
    } else {
        removeError(id);
    }
});
// MARCAÇÃO
$(formDocumentsAndAddress + 'cpf').mask("000.000.000-00", {placeholder: "___.___.___-__"});
$(formDocumentsAndAddress + 'cpf').focusout(function () {
    var id = '#' + $(this).attr("id");
    var id_caixa = $("#cpfMessage");
    var id_icon = $("#errorCPFIcon");
    removeError(id);
    $(id_icon).css('display', 'none');
    $(id_caixa).attr('data-original-title', '');
    
    const validationState = validateCpf($(id).cleanVal());
    if (!validationState.valid) {
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
    
    existsStudentWithCPF($(id).cleanVal(), function (ret) {
        if (!ret[0] && ($(id).val() != '')) {
            addError(id);
            $(id_icon).css('display', 'inline-block');
            $(id_caixa).attr('data-original-title', ret[1]);
        } else {
            removeError(id);
            $(id_icon).css('display', 'none');
            $(id_caixa).attr('data-original-title', '');
        }
    });
});

$(formDocumentsAndAddress + 'cep').mask("00000-000", {placeholder: "_____-___"});
$(formDocumentsAndAddress + 'cep').focusout(function () {
    var id = '#' + $(this).attr("id");
    if (!validateCEP($(id).cleanVal())) {
        //$(id).attr('value', '');
        addError(id, "Informe um CEP cadastrado nos correios. Apenas números são aceitos. Deve possuir no máximo 8 caracteres.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'rg_number').focusout(function () {
    var id = '#' + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateRG($(id).val())) {
        //$(id).attr('value', '');
        addError(id, "Informe um RG válido.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + 'civil_register_enrollment_number').removeAttr("maxlength").mask("000000.00.00.0000.0.00000.000.0000000-XX", {
    placeholder: "______.__.__.____._._____.___._______-__",
    translation: {
        X: {pattern: /[xX0-9]/}
    }
});

// marcacao
$(formDocumentsAndAddress + 'civil_register_enrollment_number').focusout(function () {
    var id = '#' + $(this).attr("id");
    var id_caixa = $("#registerMessage");
    var id_icon = $("#registerIcon");
    checkCivilRegisterEnrollmentNumberValidity($(formDocumentsAndAddress + 'civil_register_enrollment_number'));
    validateCivilRegisterEnrollmentNumber($(id).val(), function (ret) {
        if (!ret[0] && ($(id).val() != '')) {
            addError(id);
            $(id_icon).css('display', 'inline-block');
            $(id_caixa).attr('data-original-title', ret[1]);
        } else {
            removeError(id);
            $(id_icon).css('display', 'none');
            $(id_caixa).attr('data-original-title', '');
        }
    });
});

function checkCivilRegisterEnrollmentNumberValidity(element) {
    $(element).val($(element).val().toUpperCase());
    var id = '#' + $(element).attr("id");
    var id_caixa = $("#registerMessage");
    var id_icon = $("#registerIcon");
    var value = $(element).cleanVal();
    var valid = true;
    if (value !== "") {
        if (value.length < 32) {
            var msg = "O campo, quando preenchido, deve ter 32 caracteres.";
            addError(id);
            $(id_icon).css('display', 'inline-block');
            $(id_caixa).attr('data-original-title', msg);
            valid = false;
        }
        if (valid) {
            var year = value.substring(10, 14);
            if (year > new Date().getFullYear()) {
                var msg = "O ano de registro da certidão nova (dígitos de 11 a 14) não pode ser posterior ao ano corrente.";
                addError(id);
                $(id_icon).css('display', 'inline-block');
                $(id_caixa).attr('data-original-title', msg);
                valid = false;
            }
        }
        if (valid) {
            var birthday = $(formIdentification + 'birthday').cleanVal();
            if (birthday !== "" && birthday.length === 8) {
                var birthdayYear = birthday.substring(4, 8);
                if (year < birthdayYear) {
                    var msg = "O ano de registro da certidão nova (dígitos de 11 a 14) não pode ser anterior ao ano de nascimento.";
                    addError(id);
                    $(id_icon).css('display', 'inline-block');
                    $(id_caixa).attr('data-original-title', msg);
                    valid = false;
                }
            }
        }
    }
    if (valid) {
        removeError(id);
        $(id_icon).css('display', 'none');
        $(id_caixa).attr('data-original-title', '');
    }
    return valid;
}

$(formDocumentsAndAddress + 'civil_certification').change(function () {

    var oldDocuments = $('.js-hidden-oldDocuments-fields');
    var newDocument = $('.js-hidden-newDocument-field');

    if ($(this).val() == "") {
        oldDocuments.attr("disabled", "disabled").hide();
        newDocument.attr("disabled", "disabled").hide();

    } else {
        oldDocuments.removeAttr("disabled").show();
        newDocument.removeAttr("disabled").show();
         if ($(this).val() == 2) {
            oldDocuments.val("").attr("disabled", "disabled").hide();
        } else {
            newDocument.attr("disabled", "disabled").hide();
        }
    }
});

$(formDocumentsAndAddress + 'rg_number_expediction_date, ' + formDocumentsAndAddress + 'civil_certification_date').mask("99/99/9999");
$(formDocumentsAndAddress + 'rg_number_expediction_date, ' + formDocumentsAndAddress + 'civil_certification_date').focusout(function () {
    var id = '#' + $(this).attr("id");
    var documentDate = stringToDate($(id).val());
    var birthday = stringToDate($(formIdentification + 'birthday').val());
    if (!validateDate($(id).val()) || birthday.asianStr > documentDate.asianStr) {
        //$(id).attr('value', '');
        addError(id, "Informe uma data válida no formato Dia/Mês/Ano. Não pode ser superior a data de nascimento do aluno.");
    } else {
        removeError(id);
    }
});


$(formIdentification + 'responsable').on('change', function () {
    if ($(this).val() == 2) {
        $('#responsable_name').show();
    } else {
        $('#responsable_name').hide();
    }
});



$(formEnrollment + 'school_admission_date').mask("00/00/0000", {placeholder: "dd/mm/aaaa"});
$(formEnrollment + 'school_admission_date').focusout(function () {
    var id = '#' + $(this).attr("id");
    var school_admission_date = stringToDate($(formEnrollment + 'school_admission_date').val());


    if ((!validateDate($(formEnrollment + 'school_admission_date').val()) || !validateYear(school_admission_date.year)) && ($(id).val() != '')) {
        //$(formIdentification + 'birthday').attr('value', '');
        addError(id, "Informe uma data válida no formato Dia/Mês/Ano. Não pode ser superior a data atual.");
    } else {
        removeError(id);
    }
});

$(document).on("change", ".vaccine-checkbox", function () {
    if ($(".vaccines-container").find(".vaccine-checkbox[code=c19du]").is(":checked")) {
        $(".vaccines-container").find(".vaccine-checkbox[code=c19pd]").prop("checked", false).attr("disabled", "disabled");
        $(".vaccines-container").find(".vaccine-checkbox[code=c19sd]").prop("checked", false).attr("disabled", "disabled");
    } else {
        $(".vaccines-container").find(".vaccine-checkbox[code=c19pd]").removeAttr("disabled");
    }
    if ($(".vaccines-container").find(".vaccine-checkbox[code=c19pd]").is(":checked")) {
        $(".vaccines-container").find(".vaccine-checkbox[code=c19du]").prop("checked", false).attr("disabled", "disabled");
        $(".vaccines-container").find(".vaccine-checkbox[code=c19sd]").removeAttr("disabled");
    } else {
        $(".vaccines-container").find(".vaccine-checkbox[code=c19sd]").prop("checked", false).attr("disabled", "disabled");
        $(".vaccines-container").find(".vaccine-checkbox[code=c19du]").removeAttr("disabled");
    }
});
$(".vaccine-checkbox").trigger("change");

$(".save-student").click(function () {
    var error = false;
    var message = "";

    if($("#errorNameIcon").css('display') == 'inline-block') {
        error = true;
        message += "Corrija o campo <b>Nome</b>.<br>";
    }

    if($("#StudentIdentification_responsable_cpf_error").length) {
        error = true;
        message += "Corrija o campo <b>CPF do Responsável</b>.<br>";
    }

    if($("#errorCPFIcon").css('display') == 'inline-block') {
        error = true;
        message += "Corrija o campo <b>Nº do CPF</b>.<br>";
    }

    if($("#errorTermIcon").css('display') == 'inline-block') {
        error = true;
        message += "Corrija o campo <b>Nº do Termo</b>.<br>";
    }

    if($("#registerIcon").css('display') == 'inline-block') {
        error = true;
        message += "Corrija o campo <b>Nº da matrícula (Registro Civil - Certidão Nova)</b>.<br>";
    }

    if ($("#StudentIdentification_name").val() === "") {
        error = true;
        message += "Campo <b>Nome</b> é obrigatório.<br>";
    }
    if ($("#StudentIdentification_birthday").val() === "") {
        error = true;
        message += "Campo <b>Data de Nascimento</b> é obrigatório.<br>";
    }
    if ($("#StudentIdentification_sex").val() === "") {
        error = true;
        message += "Campo <b>Sexo</b> é obrigatório.<br>";
    }
    if ($("#StudentIdentification_color_race").val() === "") {
        error = true;
        message += "Campo <b>Cor / Raça</b> é obrigatório.<br>";
    }
    if ($("#StudentIdentification_filiation").val() === "") {
        error = true;
        message += "Campo <b>Filiação</b> é obrigatório.<br>";
    }
    if ($("#StudentIdentification_nationality").val() === "") {
        error = true;
        message += "Campo <b>Nacionalidade</b> é obrigatório.<br>";
    }
    if ($("#StudentIdentification_edcenso_nation_fk").val() === "") {
        error = true;
        message += "Campo <b>País de origem</b> é obrigatório.<br>";
    }
    if ($("#StudentDocumentsAndAddress_residence_zone").val() === "") {
        error = true;
        message += "Campo <b>Localização / Zona de residência</b> é obrigatório.<br>";
    }
    if ($("#StudentIdentification_nationality").val() === "1" && $("#StudentIdentification_edcenso_uf_fk").val() === "") {
        error = true;
        message += "Campo <b>Estado</b> é obrigatório.<br>";
    }
    if ($("#StudentIdentification_nationality").val() === "1" && $("#StudentIdentification_edcenso_city_fk").val() === "") {
        error = true;
        message += "Campo <b>Cidade</b> é obrigatório.<br>";
    }
    if ($("#StudentIdentification_deficiency").is(":checked") && !$(".deficiencies-container input[type=checkbox]:checked").length) {
        error = true;
        message += "Como o campo <b>Deficiência</b> foi preenchido, deve-se preencher pelo menos um <b>Tipo de deficiência</b>.<br>";
    }
    if ($(".deficiencies-container input[type=checkbox]").not("#StudentIdentification_deficiency_type_gifted").is(":checked") && !$(".resources-container input[type=checkbox]:checked").length) {
        error = true;
        message += "Quando o campo <b>Tipos de Deficiência</b> (exceto a última opção) for preenchido, o campo <b>Recursos requeridos em avaliações do INEP (Prova Brasil, SAEB, outros)</b> se torna obrigatório. Selecione ao menos uma opção.<br>";
    }
    if ($("#StudentDocumentsAndAddress_residence_zone").val() === "1" && $("#StudentDocumentsAndAddress_diff_location").val() === "1") {
        error = true;
        message += "Quando o campo <b>Localização / Zona de residência</b> é selecionado 'Urbano', o campo <b>Localização Diferenciada</b> não pode ser uma área de assentamento.<br>";
    }
    if ($("#StudentIdentification_filiation").val() === '1' && ($("#StudentIdentification_filiation_1").val() === "" && $("#StudentIdentification_filiation_2").val() === "")) {
        error = true;
        message += "Quando o campo <b>Filiação</b> é selecionado como 'Pai e/ou Mãe', pelo menos um dos campos <b>Nome Completo da Mãe</b> ou <b>Nome Completo do Pai</b> devem ser preenchidos.<br>";
    }
    if ($("#StudentEnrollment_public_transport").is(":checked") && $("#StudentEnrollment_transport_responsable_government").val() === "") {
        error = true;
        message += "Quando o campo <b>Transporte escolar público</b> é marcado, o campo <b>Poder público responsável pelo transporte escolar</b> é obrigatório.<br>";
    }
    if ($("#StudentEnrollment_public_transport").is(":checked") && !$("#transport_type input[type=checkbox]:checked").length) {
        error = true;
        message += "Quando o campo <b>Transporte escolar público</b> é marcado, o campo <b>Tipo de Transporte</b> é obrigatório. Selecione ao menos uma opção<br>";
    }
    if ($("#StudentEnrollment_public_transport").is(":checked")
        && ($("#StudentEnrollment_vehicle_type_van").is(":checked") && $("#StudentEnrollment_vehicle_type_microbus").is(":checked") && $("#StudentEnrollment_vehicle_type_bus").is(":checked")
            && $("#StudentEnrollment_vehicle_type_bike").is(":checked") && $("#StudentEnrollment_vehicle_type_animal_vehicle").is(":checked") && $("#StudentEnrollment_vehicle_type_other_vehicle").is(":checked"))) {
        error = true;
        message += "Não pode marcar todos os seguintes campos: <b>Van / Kombis</b>, <b>Microônibus</b>, <b>Ônibus</b>, <b>Bicicleta</b>, <b>Tração animal</b> e <b>Rodoviário - Outro</b>. Desmarque algumas dessas opções.<br>";
    }
    if ($("#StudentEnrollment_public_transport").is(":checked")
        && ($("#StudentEnrollment_vehicle_type_waterway_boat_5").is(":checked") && $("#StudentEnrollment_vehicle_type_waterway_boat_5_15").is(":checked")
            && $("#StudentEnrollment_vehicle_type_waterway_boat_15_35").is(":checked") && $("#StudentEnrollment_vehicle_type_waterway_boat_35").is(":checked"))) {
        error = true;
        message += "Não pode marcar todos os seguintes campos: <b>Embarcação - Capacidade de até 5 alunos</b>, <b>Embarcação - Entre 5 a 15 alunos</b>, <b>Embarcação - Entre 15 a 35 alunos</b> e <b>Embarcação - Acima de 35 alunos</b>. Desmarque algumas dessas opções.<br>";
    }
    if (!checkCivilRegisterEnrollmentNumberValidity($(formDocumentsAndAddress + 'civil_register_enrollment_number'))) {
        error = true;
        message += "Informe um <b>Nº de matrícula (Registro Civil - Certidão nova)</b> válido.";
    }
    if (error) {
        $("html, body").animate({scrollTop: 0}, "fast");
        $(this).closest("form").find(".student-error").html(message).show();
    } else {
        $(this).closest("form").find(".student-error").hide();
        $(formIdentification + "responsable_telephone").unmask();
        $(formIdentification + "responsable_cpf").unmask();
        $(formIdentification + "filiation_1_cpf").unmask();
        $(formIdentification + "filiation_2_cpf").unmask();
        $(formDocumentsAndAddress + "civil_register_enrollment_number").unmask();
        $(formDocumentsAndAddress + "cpf").unmask();
        $(formDocumentsAndAddress + "cep").unmask();
        $("#student input").removeAttr("disabled");
        $("#student select").removeAttr("disabled").trigger("change.select2");
        $(this).closest("form").submit();
    }
});