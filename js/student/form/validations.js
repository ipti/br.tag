$(formIdentification + "name").focusout(function () {
    let id = "#" + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    let id_error_icon = $("#errorNameIcon");
    let id_warn_icon = $("#warningNameIcon");
    let id_caixa = $("#similarMessage");
    $(id_warn_icon).css("cursor", "auto");
    removeWarning(id, id_caixa, id_warn_icon);
    removeError(id, id_caixa, id_error_icon);
    validateNamePerson($(id).val(), function (ret) {
        if (!ret[0] && $(id).val() != "") {
            removeWarning(id, id_caixa, id_warn_icon);
            if (ret[2] != null) {
                warningMessage(id, ret[2]);
                $(id_warn_icon).css("cursor", "pointer");
                addWarning(id, ret[1], id_caixa, id_warn_icon);
                $("#similarMessage").append(` ${ret[1]}` );
            } else {
                addError(id);
                $(id_error_icon).css("display", "inline-block");
                $(id_caixa).attr("data-original-title", ret[1]);
            }
        } else {
            removeError(id, id_caixa, id_error_icon);
            if (ret[0] && $(id).val() != "" && ret[1] != null) {
                if (ret[2] != null) {
                    warningMessage(id, ret[2]);
                    $(id_warn_icon).css("cursor", "pointer");
                    addWarning(id, ret[1], id_caixa, id_warn_icon);
                } else {
                    addError(id);
                    $(id_error_icon).css("display", "inline-block");
                    $(id_caixa).attr("data-original-title", ret[1]);
                }
            } else {
                removeWarning(id, id_caixa, id_warn_icon);
            }
        }
    });
});

$(formIdentification + "nis").focusout(function () {
    let id = "#" + $(this).attr("id");
    if (!validateNis($(id).val()) && $(id).val() != "") {
        addError(id, "Apenas números são aceitos. Deve possuir 11 números");
    } else {
        removeError(id);
    }
});

let SPMaskBehavior = function (val) {
    return val.replace(/\D/g, "").length === 11
        ? "(00) 00000-0000"
        : "(00) 0000-00009";
};
let spOptions = {
    onKeyPress: function (val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
    },
    placeholder: "(__) _____-____",
};

$(formIdentification + "responsable_telephone").mask(SPMaskBehavior, spOptions);
$(formIdentification + "responsable_telephone").focusout(function () {
    let id = "#" + $(this).attr("id");
    let phone = $(id).val().replace(/\D/g, '');
    if (phone.length !== 11 && phone.length !== 10) {
        addError(
            id,
            "Apenas números são aceitos. Deve possuir de 10 a 11 números."
        );
    } else {
        removeError(id);
    }
});

$(formIdentification + "responsable_cpf").mask("000.000.000-00", {
    placeholder: "___.___.___-__",
});
$(formIdentification + "responsable_cpf").focusout(function () {
    let id = "#" + $(this).attr("id");
    removeError(id);
    const validationState = validateCpf($(id).val().replace(/\D/g, ''));
    if (!validationState.valid) {
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + "civil_certification_term_number").focusout(
    function () {
        let id = "#" + $(this).attr("id");
        let id_caixa = $("#termMessage");
        let id_icon = $("#errorTermIcon");
        removeError(id);
        $(id_icon).css("display", "none");
        $(id_caixa).attr("data-original-title", "");
        validateCivilCertificationTermNumber($(id).val(), function (ret) {
            if (!ret[0] && $(id).val() != "") {
                addError(id);
                $(id_icon).css("display", "inline-block");
                $(id_caixa).attr("data-original-title", ret[1]);
            } else {
                removeError(id);
                $(id_icon).css("display", "none");
                $(id_caixa).attr("data-original-title", "");
            }
        });
    }
);

$(formIdentification + "filiation_1_cpf").mask("000.000.000-00", {
    placeholder: "___.___.___-__",
});
$(formIdentification + "filiation_1_cpf").focusout(function () {
    let id = "#" + $(this).attr("id");
    const validationState = validateCpf($(id).val().replace(/\D/g, ''));
    if (!validationState.valid) {
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
});

$(formIdentification + "filiation_2_cpf").mask("000.000.000-00", {
    placeholder: "___.___.___-__",
});
$(formIdentification + "filiation_2_cpf").focusout(function () {
    let id = "#" + $(this).attr("id");
    const validationState = validateCpf($(id).val().replace(/\D/g, ''));
    if (!validationState.valid) {
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + "cns").focusout(function () {
    let id = "#" + $(this).attr("id");
    if (!validateCns($(id).val())) {
        addError(id, "Apenas números são aceitos. Deve possuir 15 números");
    } else {
        removeError(id);
    }
});

initDateFieldMaskAndValidation(formIdentification + "birthday");
initDateFieldMaskAndValidation(formIdentification + "filiation_1_birthday");
initDateFieldMaskAndValidation(formIdentification + "filiation_2_birthday");

$(formIdentification + "filiation").change(function () {
    let simple = getUrlVars()["simple"];
    $(".js-disabled-finputs").hide();
    if ($(formIdentification + "filiation").val() == 1) {
        $(".js-disabled-finputs").show();
        $(formIdentification + "filiation_1")
            .closest(".js-visibility-fname")
            .show();
        $(formIdentification + "filiation_2")
            .closest(".js-visibility-fname")
            .show();
    } else {
        $(".js-finput-clear").val("");
        $(formIdentification + "filiation_1")
            .closest(".js-visibility-fname")
            .css("display", simple === "1" ? "none" : "block");
        $(formIdentification + "filiation_2")
            .closest(".js-visibility-fname")
            .css("display", simple === "1" ? "none" : "block");
    }
});
$(formIdentification + "filiation").trigger("change");

$(formIdentification + "filiation_1").focusout(function () {
    let id = "#" + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    removeError(id);
    validateNamePerson($(id).val(), function (ret) {
        if (!ret[0]) {
            $(id).attr("value", "");
            addError(id, ret[1]);
        } else {
            removeError(id);
            if (
                $(formIdentification + "filiation_1").val() !== "" &&
                $(formIdentification + "filiation_2").val() !== "" &&
                $(formIdentification + "filiation_1").val() ===
                    $(formIdentification + "filiation_2").val()
            ) {
                $(formIdentification + "filiation_1").attr("value", "");
                addError(id, "O campo não deve ser igual à outra filiação.");
            } else {
                removeError(id);
            }
        }
    });
});

$(formIdentification + "filiation_2").focusout(function () {
    let id = "#" + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    removeError(id);
    validateNamePerson($(id).val(), function (ret) {
        if (!ret[0]) {
            $(id).attr("value", "");
            addError(id, ret[1]);
        } else {
            removeError(id);
            if (
                $(formIdentification + "filiation_1").val() !== "" &&
                $(formIdentification + "filiation_2").val() !== "" &&
                $(formIdentification + "filiation_1").val() ===
                    $(formIdentification + "filiation_2").val()
            ) {
                $(formIdentification + "filiation_2").attr("value", "");
                addError(id, "O campo não deve ser igual à outra filiação.");
            } else {
                removeError(id);
            }
        }
    });
});

$(formIdentification + "nationality").change(function () {
    let nationality = ".nationality-sensitive";
    let br = nationality + ".br";
    let nobr = nationality + ".no-br";
    let simple = getUrlVars()["simple"];
    const visbilityWhenSimple = simple === "1" ? "none" : "block";
    $(nationality).attr("disabled", "disabled");
    if ($(this).val() == 3) {
        $(nobr).removeAttr("disabled");
        $(formIdentification + "edcenso_nation_fk")
            .trigger("change")
            .select2("readonly", false);
        $(formIdentification + "edcenso_uf_fk")
            .val("")
            .trigger("change.select2");
        $(formIdentification + "edcenso_city_fk")
            .val("")
            .trigger("change.select2");
        $(formIdentification + "edcenso_uf_fk")
            .closest(".js-change-required")
            .css("display", visbilityWhenSimple)
            .find("label")
            .removeClass("required")
            .html("Estado");
        $(formIdentification + "edcenso_city_fk")
            .closest(".js-change-required")
            .css("display", visbilityWhenSimple)
            .find("label")
            .removeClass("required")
            .html("Cidade");
    } else if ($(this).val() == "") {
        $(formIdentification + "edcenso_nation_fk")
            .val(null)
            .trigger("change")
            .attr("disabled", "disabled");
        $(nationality).attr("disabled", "disabled");
        $(formIdentification + "edcenso_uf_fk")
            .val("")
            .trigger("change.select2")
            .closest(".js-change-required")
            .css("display", visbilityWhenSimple)
            .find("label")
            .removeClass("required")
            .html("Estado");
        $(formIdentification + "edcenso_city_fk")
            .val("")
            .trigger("change.select2")
            .closest(".js-change-required")
            .css("display", visbilityWhenSimple)
            .find("label")
            .removeClass("required")
            .html("Cidade");
    } else {
        $(formIdentification + "edcenso_nation_fk")
            .val(76)
            .trigger("change")
            .removeAttr("disabled")
            .select2("readonly", true);
        $(br).removeAttr("disabled");
        $(formDocumentsAndAddress + "civil_certification").trigger("change");
        if ($(this).val() == "1") {
            $(formIdentification + "edcenso_uf_fk")
                .removeAttr("disabled")
                .closest(".js-change-required")
                .show()
                .find("label")
                .addClass("required")
                .html("Estado");
            $(formIdentification + "edcenso_city_fk")
                .removeAttr("disabled")
                .closest(".js-change-required")
                .show()
                .find("label")
                .addClass("required")
                .html("Cidade");
        } else {
            $(formIdentification + "edcenso_uf_fk")
                .val("")
                .trigger("change.select2")
                .attr("disabled", "disabled")
                .closest(".js-change-required")
                .css("display", visbilityWhenSimple)
                .find("label")
                .removeClass("required")
                .html("Estado");
            $(formIdentification + "edcenso_city_fk")
                .val("")
                .trigger("change.select2")
                .attr("disabled", "disabled")
                .closest(".js-change-required")
                .css("display", visbilityWhenSimple)
                .find("label")
                .removeClass("required")
                .html("Cidade");
        }
    }
});
$(formIdentification + "nationality").trigger("change");

let deficiency = formIdentification + "deficiency"; //17

let dtBlind = formIdentification + "deficiency_type_blindness"; //18
let dtLowVi = formIdentification + "deficiency_type_low_vision"; //19
let dtMonVi = formIdentification + "deficiency_type_monocular_vision"; //20
let dtDeafn = formIdentification + "deficiency_type_deafness"; //20
let dtDisab = formIdentification + "deficiency_type_disability_hearing"; //21
let dtDeafB = formIdentification + "deficiency_type_deafblindness"; //22
let dtPhisi = formIdentification + "deficiency_type_phisical_disability"; //23
let dtIntel = formIdentification + "deficiency_type_intelectual_disability"; //24
let dtAutis = formIdentification + "deficiency_type_autism"; //26
let dtAspen = formIdentification + "deficiency_type_aspenger_syndrome"; //27
let dtRettS = formIdentification + "deficiency_type_rett_syndrome"; //28
let dtChild =
    formIdentification + "deficiency_type_childhood_disintegrative_disorder"; //29
let dtGifte = formIdentification + "deficiency_type_gifted"; //30
let allDeficiency =
    dtBlind +
    "," +
    dtLowVi +
    "," +
    dtMonVi +
    "," +
    dtDeafn +
    "," +
    dtDisab +
    "," +
    dtDeafB +
    "," +
    dtGifte +
    "," +
    dtPhisi +
    "," +
    dtIntel +
    "," +
    dtAutis +
    "," +
    dtAspen +
    "," +
    dtRettS +
    "," +
    dtChild;
let defLessGifited =
    dtBlind +
    "," +
    dtLowVi +
    "," +
    dtDeafn +
    "," +
    dtDisab +
    "," +
    dtDeafB +
    "," +
    dtPhisi +
    "," +
    dtIntel +
    "," +
    dtAutis +
    "," +
    dtAspen +
    "," +
    dtRettS +
    "," +
    dtChild;

let rAidLec = formIdentification + "resource_aid_lector"; //31
let rAidTra = formIdentification + "resource_aid_transcription"; //32
let rIntGui = formIdentification + "resource_interpreter_guide"; //33
let rIntLib = formIdentification + "resource_interpreter_libras"; //34
let rLipRea = formIdentification + "resource_lip_reading"; //35
let rZoom18 = formIdentification + "resource_zoomed_test_18"; //36
let rZoom24 = formIdentification + "resource_zoomed_test_24"; //38
let rBraile = formIdentification + "resource_braille_test"; //39
let rProLan = formIdentification + "resource_proof_language"; //39
let rCdAudi = formIdentification + "resource_cd_audio"; //39
let rVidLib = formIdentification + "resource_video_libras"; //39
let allResource =
    rAidLec +
    "," +
    rAidTra +
    "," +
    rIntGui +
    "," +
    rIntLib +
    "," +
    rLipRea +
    "," +
    rZoom18 +
    "," +
    rZoom24 +
    "," +
    rBraile +
    "," +
    rProLan +
    "," +
    rCdAudi +
    "," +
    rVidLib;

let rNone = formIdentification + "resource_none"; //40

let defReadOnly;
let recReadOnly;

$(allDeficiency).click(function () {
    defReadOnly = $(this).attr("readonly") == "readonly";
});
$(allResource).click(function () {
    recReadOnly = $(this).attr("readonly") == "readonly";
});

$(formIdentification + "deficiency_type_blindness").on("click", function () {
    if ($(this).is(":checked")) {
        $(formIdentification + "deficiency_type_low_vision")
            .removeAttr("checked", "checked")
            .attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_monocular_vision")
            .removeAttr("checked", "checked")
            .attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafness")
            .removeAttr("checked", "checked")
            .attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafblindness")
            .removeAttr("checked", "checked")
            .attr("disabled", "disabled");
    } else {
        $(formIdentification + "deficiency_type_low_vision").removeAttr(
            "disabled"
        );
        $(formIdentification + "deficiency_type_monocular_vision").removeAttr(
            "disabled"
        );
        if (
            !$(formIdentification + "deficiency_type_disability_hearing").is(
                ":checked"
            )
        ) {
            $(formIdentification + "deficiency_type_deafness").removeAttr(
                "disabled"
            );
            $(formIdentification + "deficiency_type_deafblindness").removeAttr(
                "disabled"
            );
        }
    }
});

$(formIdentification + "deficiency_type_low_vision").on("click", function () {
    if ($(this).is(":checked")) {
        $(formIdentification + "deficiency_type_blindness")
            .removeAttr("checked", "checked")
            .attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafblindness")
            .removeAttr("checked", "checked")
            .attr("disabled", "disabled");
    } else {
        if (
            !$(formIdentification + "deficiency_type_monocular_vision").is(
                ":checked"
            ) &&
            !$(formIdentification + "deficiency_type_deafness").is(":checked")
        ) {
            $(formIdentification + "deficiency_type_blindness").removeAttr(
                "disabled"
            );
            if (
                !$(
                    formIdentification + "deficiency_type_disability_hearing"
                ).is(":checked")
            ) {
                $(
                    formIdentification + "deficiency_type_deafblindness"
                ).removeAttr("disabled");
            }
        }
    }
});

$(formIdentification + "deficiency_type_monocular_vision").on(
    "click",
    function () {
        if ($(this).is(":checked")) {
            $(formIdentification + "deficiency_type_blindness")
                .removeAttr("checked", "checked")
                .attr("disabled", "disabled");
            $(formIdentification + "deficiency_type_deafblindness")
                .removeAttr("checked", "checked")
                .attr("disabled", "disabled");
        } else if (
            !$(formIdentification + "deficiency_type_low_vision").is(
                ":checked"
            ) &&
            !$(formIdentification + "deficiency_type_deafness").is(":checked")
        ) {
            $(formIdentification + "deficiency_type_blindness").removeAttr(
                "disabled"
            );
            if (
                !$(
                    formIdentification + "deficiency_type_disability_hearing"
                ).is(":checked")
            ) {
                $(
                    formIdentification + "deficiency_type_deafblindness"
                ).removeAttr("disabled");
            }
        }
    }
);

$(formIdentification + "deficiency_type_deafness").on("click", function () {
    if ($(this).is(":checked")) {
        $(formIdentification + "deficiency_type_blindness")
            .removeAttr("checked", "checked")
            .attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_disability_hearing")
            .removeAttr("checked", "checked")
            .attr("disabled", "disabled");
        $(formIdentification + "deficiency_type_deafblindness")
            .removeAttr("checked", "checked")
            .attr("disabled", "disabled");
    } else {
        if (
            !$(formIdentification + "deficiency_type_low_vision").is(
                ":checked"
            ) &&
            !$(formIdentification + "deficiency_type_monocular_vision").is(
                ":checked"
            )
        ) {
            $(formIdentification + "deficiency_type_blindness").removeAttr(
                "disabled"
            );
            $(formIdentification + "deficiency_type_deafblindness").removeAttr(
                "disabled"
            );
        }
        $(formIdentification + "deficiency_type_disability_hearing").removeAttr(
            "disabled"
        );
    }
});

$(formIdentification + "deficiency_type_disability_hearing").on(
    "click",
    function () {
        if ($(this).is(":checked")) {
            $(formIdentification + "deficiency_type_deafness")
                .removeAttr("checked", "checked")
                .attr("disabled", "disabled");
            $(formIdentification + "deficiency_type_deafblindness")
                .removeAttr("checked", "checked")
                .attr("disabled", "disabled");
        } else if (
                !$(formIdentification + "deficiency_type_blindness").is(
                    ":checked"
                )
            ) {
                $(formIdentification + "deficiency_type_deafness").removeAttr(
                    "disabled"
                );
                if (
                    !$(formIdentification + "deficiency_type_low_vision").is(
                        ":checked"
                    ) &&
                    !$(
                        formIdentification + "deficiency_type_monocular_vision"
                    ).is(":checked")
                ) {
                    $(
                        formIdentification + "deficiency_type_deafblindness"
                    ).removeAttr("disabled");
                }
            }
    }
);
$(formIdentification + "deficiency_type_deafblindness").on(
    "click",
    function () {
        if ($(this).is(":checked")) {
            $(formIdentification + "deficiency_type_blindness")
                .removeAttr("checked", "checked")
                .attr("disabled", "disabled");
            $(formIdentification + "deficiency_type_low_vision")
                .removeAttr("checked", "checked")
                .attr("disabled", "disabled");
            $(formIdentification + "deficiency_type_monocular_vision")
                .removeAttr("checked", "checked")
                .attr("disabled", "disabled");
            $(formIdentification + "deficiency_type_deafness")
                .removeAttr("checked", "checked")
                .attr("disabled", "disabled");
            $(formIdentification + "deficiency_type_disability_hearing")
                .removeAttr("checked", "checked")
                .attr("disabled", "disabled");
        } else {
            $(formIdentification + "deficiency_type_blindness").removeAttr(
                "disabled"
            );
            $(formIdentification + "deficiency_type_low_vision").removeAttr(
                "disabled"
            );
            $(
                formIdentification + "deficiency_type_monocular_vision"
            ).removeAttr("disabled");
            $(formIdentification + "deficiency_type_deafness").removeAttr(
                "disabled"
            );
            $(
                formIdentification + "deficiency_type_disability_hearing"
            ).removeAttr("disabled");
        }
    }
);

$(formIdentification + "deficiency_type_intelectual_disability").on(
    "click",
    function () {
        if ($(this).is(":checked")) {
            $(formIdentification + "deficiency_type_gifted").removeAttr(
                "checked",
                "checked"
            );
            $(formIdentification + "deficiency_type_gifted")
                .add()
                .attr("disabled", "disabled");
        } else {
            $(formIdentification + "deficiency_type_gifted")
                .add()
                .removeAttr("disabled", "disabled");
        }
    }
);

$(formIdentification + "deficiency_type_gifted").on("click", function () {
    if ($(this).is(":checked")) {
        $(
            formIdentification + "deficiency_type_intelectual_disability"
        ).removeAttr("checked", "checked");
        $(formIdentification + "deficiency_type_intelectual_disability")
            .add()
            .attr("disabled", "disabled");
    } else {
        $(formIdentification + "deficiency_type_intelectual_disability")
            .add()
            .removeAttr("disabled", "disabled");
    }
});

$("#resource_type input").change(function () {
    if (recReadOnly) {
        $(this).attr("checked", false);
    }

    let checked = !$(this).is(":checked");
    $("#resource_type " + rNone).attr("checked", checked);

    let rz16 = $("#resource_type " + rZoom16);
    let rz20 = $("#resource_type " + rZoom20);
    let rz24 = $("#resource_type " + rZoom24);
    rz16.attr("readonly", false);
    rz20.attr("readonly", false);
    rz24.attr("readonly", false);

    if (rz16.is(":checked")) {
        $(rz20).attr("checked", false).attr("readonly", true);
        $(rz24).attr("checked", false).attr("readonly", true);
    }
    if (rz20.is(":checked")) {
        $(rz16).attr("checked", false).attr("readonly", true);
        $(rz24).attr("checked", false).attr("readonly", true);
    }
    if (rz24.is(":checked")) {
        $(rz16).attr("checked", false).attr("readonly", true);
        $(rz20).attr("checked", false).attr("readonly", true);
    }
});

$("#resource_type " + rNone).change(function () {
    if (recReadOnly) {
        $(this).attr("checked", false);
    }

    if ($(this).is(":checked")) {
        $(allResource).attr("checked", false);
    }
});

$(deficiency).change(function () {
    if ($(this).is(":checked")) {
        $(allDeficiency).removeAttr("disabled");
        $("#StudentIdentification_deficiencies")
            .parent(".js-change-required")
            .show()
            .find("label")
            .addClass("required");
        $("#StudentIdentification_deficiency_type_blindness").trigger(
            "change",
            [true]
        );
        $(".resources-container").show();
    } else {
        $(allDeficiency).attr("disabled", "disabled").removeAttr("checked");
        $("#StudentIdentification_deficiencies")
            .parent(".js-visibility-deficiencies")
            .hide();
        $("#StudentIdentification_resource_aid_lector")
            .closest(".js-visibility-dresource")
            .hide();
        $(".resources-container").hide();
        $(".resources-container input[type=checkbox]").prop("checked", false);
    }
});
$(deficiency).trigger("change");

$(".deficiencies-container input[type=checkbox]").change(function () {
    if (
        $(".deficiencies-container input[type=checkbox]")
            .not("#StudentIdentification_deficiency_type_gifted")
            .is(":checked")
    ) {
        $(".resources-container")
            .find(".control-label")
            .addClass("required")
            .html(
                "Recursos requeridos em avaliações do INEP (Prova Brasil, SAEB, outros) *"
            );
    } else {
        $(".resources-container")
            .find(".control-label")
            .removeClass("required")
            .html(
                "Recursos requeridos em avaliações do INEP (Prova Brasil, SAEB, outros)"
            );
    }
});
$(".deficiencies-container input[type=checkbox]").trigger("change", [true]);

$(formDocumentsAndAddress + "address").focusout(function () {
    let id = "#" + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddress($(id).val())) {
        addError(
            id,
            "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1."
        );
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + "number").focusout(function () {
    let id = "#" + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddressNumber($(id).val())) {
        addError(
            id,
            "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1."
        );
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + "complement").focusout(function () {
    let id = "#" + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddressComplement($(id).val())) {
        addError(
            id,
            "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1."
        );
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + "neighborhood").focusout(function () {
    let id = "#" + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateStudentAddressNeighborhood($(id).val())) {
        addError(
            id,
            "O campo aceita somente caracteres de A a Z, 0 a 9, ª, º, espaço e -. Tamanho mínimo: 1."
        );
    } else {
        removeError(id);
    }
});
// MARCAÇÃO
$(formDocumentsAndAddress + "cpf").mask("000.000.000-00", {
    placeholder: "___.___.___-__",
});
$(formDocumentsAndAddress + "cpf").focusout(function () {
    let id = "#" + $(this).attr("id");
    let id_caixa = $("#cpfMessage");
    let id_icon = $("#errorCPFIcon");
    removeError(id);
    $(id_icon).css("display", "none");
    $(id_caixa).attr("data-original-title", "");

    const validationState = validateCpf($(id).val().replace(/\D/g, ''));
    if (!validationState.valid) {
        addError(id, "Informe um CPF válido. Deve possuir apenas números.");
    } else {
        removeError(id);
    }

    var idStudent = new URLSearchParams(window.location.search).get('id');
    existsStudentWithCPF($(id).val().replace(/\D/g, ''), idStudent, function (ret) {
        if (!ret[0] && $(id).val() != "") {
            addError(id);
            $(id_icon).css("display", "inline-block");
            addError(id, "Cpf do estudante já cadastrado no sistema.");
        } else {
            removeError(id);
            $(id_icon).css("display", "none");
            $(id_caixa).attr("data-original-title", "");
        }
    });
});

$(formDocumentsAndAddress + "cep").mask("00000-000", {
    placeholder: "_____-___",
});
$(formDocumentsAndAddress + "cep").focusout(function () {
    let id = "#" + $(this).attr("id");
    if (!validateCEP($(id).val().replace(/\D/g, ''))) {
        addError(
            id,
            "Informe um CEP cadastrado nos correios. Apenas números são aceitos. Deve possuir no máximo 8 caracteres."
        );
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + "rg_number").focusout(function () {
    let id = "#" + $(this).attr("id");
    $(id).val($(id).val().toUpperCase());
    if (!validateRG($(id).val())) {
        addError(id, "Informe um RG válido.");
    } else {
        removeError(id);
    }
});

$(formDocumentsAndAddress + "civil_register_enrollment_number")
    .removeAttr("maxlength")
    .mask("000000.00.00.0000.0.00000.000.0000000-XX", {
        placeholder: "______.__.__.____._._____.___._______-__",
        translation: {
            X: { pattern: /[xX0-9]/ },
        },
    });

// marcacao
$(formDocumentsAndAddress + "civil_register_enrollment_number").focusout(
    function () {
        let id = "#" + $(this).attr("id");
        let id_caixa = $("#registerMessage");
        let id_icon = $("#registerIcon");
        checkCivilRegisterEnrollmentNumberValidity(
            $(formDocumentsAndAddress + "civil_register_enrollment_number")
        );
        validateCivilRegisterEnrollmentNumber($(id).val(), function (ret) {
            if (!ret[0] && $(id).val() != "") {
                addError(id);
                $(id_icon).css("display", "inline-block");
                $(id_caixa).attr("data-original-title", ret[1]);
            } else {
                removeError(id);
                $(id_icon).css("display", "none");
                $(id_caixa).attr("data-original-title", "");
            }
        });
    }
);

function checkCivilRegisterEnrollmentNumberValidity(element) {
    $(element).val($(element).val().toUpperCase());
    let id = "#" + $(element).attr("id");
    let id_caixa = $("#registerMessage");
    let id_icon = $("#registerIcon");
    let value = $(element).val().replace(/\D/g, '');
    let valid = true;
    if (value !== "") {
        if (value.length < 32) {
            let msg = "O campo, quando preenchido, deve ter 32 caracteres.";
            addError(id);
            $(id_icon).css("display", "inline-block");
            $(id_caixa).attr("data-original-title", msg);
            valid = false;
        }
        if (valid) {
            let year = value.substring(10, 14);
            if (year > new Date().getFullYear()) {
                let msg =
                    "O ano de registro da certidão nova (dígitos de 11 a 14) não pode ser posterior ao ano corrente.";
                addError(id);
                $(id_icon).css("display", "inline-block");
                $(id_caixa).attr("data-original-title", msg);
                valid = false;
            }
        }
        if (valid) {
            let year = value.substring(10, 14);
            var selectedDate = $("#initial_date_picker").val();
            let birthday = selectedDate.replace(/\//g, '');
            if (birthday !== "" && birthday.length === 8) {
                let birthdayYear = birthday.substring(4, 8);
                if (year < birthdayYear) {
                    let msg =
                        "O ano de registro da certidão nova (dígitos de 11 a 14) não pode ser anterior ao ano de nascimento.";
                    addError(id);
                    $(id_icon).css("display", "inline-block");
                    $(id_caixa).attr("data-original-title", msg);
                    valid = false;
                }
            }
        }
    }
    if (valid) {
        removeError(id);
        $(id_icon).css("display", "none");
        $(id_caixa).attr("data-original-title", "");
    }
    return valid;
}

$(formDocumentsAndAddress + "civil_certification").change(function () {
    let oldDocuments = $(".js-hidden-oldDocuments-fields");
    let newDocument = $(".js-hidden-newDocument-field");

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

$(
    formDocumentsAndAddress +
        "rg_number_expediction_date, " +
        formDocumentsAndAddress +
        "civil_certification_date"
).mask("99/99/9999", { placeholder: "dd/mm/aaaa" });
$(
    formDocumentsAndAddress +
        "rg_number_expediction_date, " +
        formDocumentsAndAddress +
        "civil_certification_date"
).focusout(function () {
    let id = "#" + $(this).attr("id");
    let documentDate = stringToDate($(id).val());
    debugger
    let birthday = stringToDate($(`[name="StudentIdentification[birthday]"]`).val());
    let isAfterBirthday = birthday?.asianStr != null && documentDate?.asianStr != null && birthday?.asianStr > documentDate?.asianStr
    if (!validateDate($(id).val()) || isAfterBirthday) {
        addError(
            id,
            "Informe uma data válida no formato Dia/Mês/Ano. Não pode ser superior a data de nascimento do aluno."
        );
    } else {
        removeError(id);
    }
});

$(formIdentification + "responsable").on("change", function () {
    if ($(this).val() == 2) {
        $("#responsable_name").show();
    } else {
        $("#responsable_name").hide();
    }
});

$(formEnrollment + "school_admission_date").mask("00/00/0000", {
    placeholder: "dd/mm/aaaa",
});
$(formEnrollment + "school_admission_date").focusout(function () {
    let id = "#" + $(this).attr("id");
    let school_admission_date = stringToDate(
        $(formEnrollment + "school_admission_date").val()
    );

    if (
        (!validateDate($(formEnrollment + "school_admission_date").val()) ||
            !validateYear(school_admission_date.year)) &&
        $(id).val() != ""
    ) {
        addError(
            id,
            "Informe uma data válida no formato Dia/Mês/Ano. Não pode ser superior a data atual."
        );
    } else {
        removeError(id);
    }
});

$(document).on("change", ".vaccine-checkbox", function () {
    if (
        $(".vaccines-container")
            .find(".vaccine-checkbox[code=c19du]")
            .is(":checked")
    ) {
        $(".vaccines-container")
            .find(".vaccine-checkbox[code=c19pd]")
            .prop("checked", false)
            .attr("disabled", "disabled");
        $(".vaccines-container")
            .find(".vaccine-checkbox[code=c19sd]")
            .prop("checked", false)
            .attr("disabled", "disabled");
    } else {
        $(".vaccines-container")
            .find(".vaccine-checkbox[code=c19pd]")
            .removeAttr("disabled");
    }
    if (
        $(".vaccines-container")
            .find(".vaccine-checkbox[code=c19pd]")
            .is(":checked")
    ) {
        $(".vaccines-container")
            .find(".vaccine-checkbox[code=c19du]")
            .prop("checked", false)
            .attr("disabled", "disabled");
        $(".vaccines-container")
            .find(".vaccine-checkbox[code=c19sd]")
            .removeAttr("disabled");
    } else {
        $(".vaccines-container")
            .find(".vaccine-checkbox[code=c19sd]")
            .prop("checked", false)
            .attr("disabled", "disabled");
        $(".vaccines-container")
            .find(".vaccine-checkbox[code=c19du]")
            .removeAttr("disabled");
    }
});
$(".vaccine-checkbox").trigger("change");

$(".save-student").click(function () {
    let error = false;
    let message = "";

    if (
        $("#StudentIdentification_bf_participator").is(":checked") &&
        $("#StudentDocumentsAndAddress_nis").val() == ""
    ) {
        error = true;
        message +=
            "O campo <b>NIS</b> é obrigatório para alunos participantes do Bolsa Família.<br>";
    }
    if (
        $("#StudentEnrollment_classroom_fk").find(":selected").data("ismulti") == 1 &&
        $("#StudentEnrollment_edcenso_stage_vs_modality_fk").val() == ""
    ) {
        error = true;
        message +=
            "Quando a turma é multiseriada o campo <b>Etapa de Ensino</b> é obrigatório.<br>";
    }

    if ($("#errorNameIcon").css("display") == "inline-block") {
        error = true;
        message += "Corrija o campo <b>Nome</b>.<br>";
    }

    if ($("#StudentIdentification_responsable_cpf_error").length) {
        error = true;
        message += "Corrija o campo <b>CPF do Responsável</b>.<br>";
    }

    if ($("#errorCPFIcon").css("display") == "inline-block") {
        error = true;
        message += "Corrija o campo <b>Nº do CPF</b>.<br>";
    }

    if ($("#errorTermIcon").css("display") == "inline-block") {
        error = true;
        message += "Corrija o campo <b>Nº do Termo</b>.<br>";
    }

    if ($("#registerIcon").css("display") == "inline-block") {
        error = true;
        message +=
            "Corrija o campo <b>Nº da matrícula (Registro Civil - Certidão Nova)</b>.<br>";
    }

    /**
     * DADOS DO ALUNO
     **/
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

    /**
     * ENDEREÇO
     **/
    if (
        $("#StudentIdentification_nationality").val() === "1" &&
        $("#StudentIdentification_edcenso_uf_fk").val() === ""
    ) {
        error = true;
        message += "Campo <b>Estado</b> é obrigatório.<br>";
    }
    if (
        $("#StudentIdentification_nationality").val() === "1" &&
        $("#StudentIdentification_edcenso_city_fk").val() === ""
    ) {
        error = true;
        message += "Campo <b>Cidade</b> é obrigatório.<br>";
    }
    if ($("#StudentDocumentsAndAddress_residence_zone").val() === "") {
        error = true;
        message +=
            "Campo <b>Localização / Zona de residência</b> é obrigatório.<br>";
    }

    if (sedspEnable) {
        if ($("#StudentDocumentsAndAddress_edcenso_uf_fk").val() === "") {
            error = true;
            message += "Campo <b>Estado</b> é obrigatório.<br>";
        }
        if ($("#StudentDocumentsAndAddress_edcenso_city_fk").val() === "") {
            error = true;
            message += "Campo <b>Cidade</b> é obrigatório.<br>";
        }
        if ($("#StudentDocumentsAndAddress_neighborhood").val() === "") {
            error = true;
            message += "Campo <b>Bairro / Povoado</b> é obrigatório.<br>";
        }
        if ($("#StudentDocumentsAndAddress_cep").val() === "") {
            error = true;
            message += "Campo <b>CEP</b> é obrigatório.<br>";
        }
        if ($("#StudentDocumentsAndAddress_address").val() === "") {
            error = true;
            message += "Campo <b>Endereço</b> é obrigatório.<br>";
        }
        if ($("#StudentDocumentsAndAddress_number").val() === "") {
            error = true;
            message += "Campo <b>N°</b> é obrigatório.<br>";
        }
        $("#StudentDocumentsAndAddress_number").prop("required", true);
    }

    /**
     * SAÚDE
     **/
    if (
        $("#StudentIdentification_deficiency").is(":checked") &&
        !$(".deficiencies-container input[type=checkbox]:checked").length
    ) {
        error = true;
        message +=
            "Como o campo <b>Deficiência</b> foi preenchido, deve-se preencher pelo menos um <b>Tipo de deficiência</b>.<br>";
    }
    if (
        $(".deficiencies-container input[type=checkbox]")
            .not("#StudentIdentification_deficiency_type_gifted")
            .is(":checked") &&
        !$(".resources-container input[type=checkbox]:checked").length
    ) {
        error = true;
        message +=
            "Quando o campo <b>Tipos de Deficiência</b> (exceto Altas Habilidades / Super Dotação) for preenchido, o campo <b>Recursos requeridos em avaliações do INEP (Prova Brasil, SAEB, outros)</b> se torna obrigatório. Selecione ao menos uma opção.<br>";
    }
    if (
        $("#StudentDocumentsAndAddress_residence_zone").val() === "1" &&
        $("#StudentDocumentsAndAddress_diff_location").val() === "1"
    ) {
        error = true;
        message +=
            "Quando o campo <b>Localização / Zona de residência</b> é selecionado 'Urbano', o campo <b>Localização Diferenciada</b> não pode ser uma área de assentamento.<br>";
    }
    if (
        $("#StudentIdentification_filiation").val() === "1" &&
        $("#StudentIdentification_filiation_1").val() === "" &&
        $("#StudentIdentification_filiation_2").val() === ""
    ) {
        error = true;
        message +=
            "Quando o campo <b>Filiação</b> é selecionado como 'Pai e/ou Mãe', pelo menos um dos campos <b>Nome Completo da Filiação</b> ou <b>Nome Completo do Pai</b> devem ser preenchidos.<br>";
    }
    if (
        $("#StudentEnrollment_public_transport").is(":checked") &&
        $("#StudentEnrollment_transport_responsable_government").val() === ""
    ) {
        error = true;
        message +=
            "Quando o campo <b>Transporte escolar público</b> é marcado, o campo <b>Poder público responsável pelo transporte escolar</b> é obrigatório.<br>";
    }
    if (
        $("#StudentEnrollment_public_transport").is(":checked") &&
        !$("#transport_type input[type=checkbox]:checked").length
    ) {
        error = true;
        message +=
            "Quando o campo <b>Transporte escolar público</b> é marcado, o campo <b>Tipo de Transporte</b> é obrigatório. Selecione ao menos uma opção<br>";
    }
    if (
        $("#StudentEnrollment_public_transport").is(":checked") &&
        $("#StudentEnrollment_vehicle_type_van").is(":checked") &&
        $("#StudentEnrollment_vehicle_type_microbus").is(":checked") &&
        $("#StudentEnrollment_vehicle_type_bus").is(":checked") &&
        $("#StudentEnrollment_vehicle_type_bike").is(":checked") &&
        $("#StudentEnrollment_vehicle_type_animal_vehicle").is(":checked") &&
        $("#StudentEnrollment_vehicle_type_other_vehicle").is(":checked")
    ) {
        error = true;
        message +=
            "Não pode marcar todos os seguintes campos: <b>Van / Kombis</b>, <b>Microônibus</b>, <b>Ônibus</b>, <b>Bicicleta</b>, <b>Tração animal</b> e <b>Rodoviário - Outro</b>. Desmarque algumas dessas opções.<br>";
    }
    if (
        $("#StudentEnrollment_public_transport").is(":checked") &&
        $("#StudentEnrollment_vehicle_type_waterway_boat_5").is(":checked") &&
        $("#StudentEnrollment_vehicle_type_waterway_boat_5_15").is(
            ":checked"
        ) &&
        $("#StudentEnrollment_vehicle_type_waterway_boat_15_35").is(
            ":checked"
        ) &&
        $("#StudentEnrollment_vehicle_type_waterway_boat_35").is(":checked")
    ) {
        error = true;
        message +=
            "Não pode marcar todos os seguintes campos: <b>Embarcação - Capacidade de até 5 alunos</b>, <b>Embarcação - Entre 5 a 15 alunos</b>, <b>Embarcação - Entre 15 a 35 alunos</b> e <b>Embarcação - Acima de 35 alunos</b>. Desmarque algumas dessas opções.<br>";
    }
    if (
        !checkCivilRegisterEnrollmentNumberValidity(
            $(formDocumentsAndAddress + "civil_register_enrollment_number")
        )
    ) {
        error = true;
        message +=
            "Informe um <b>Nº de matrícula (Registro Civil - Certidão nova)</b> válido.";
    }
    if (error) {
        $("html, body").animate({ scrollTop: 0 }, "fast");
        $(this)
            .closest("form")
            .find(".student-alert")
            .addClass("alert-error")
            .removeClass("alert-warning")
            .removeClass("alert-success")
            .html(message)
            .show();
    } else {
        $(this).closest("form").find(".student-alert").hide();
        $(formIdentification + "responsable_telephone").unmask();
        $(formIdentification + "responsable_cpf").unmask();
        $(formIdentification + "filiation_1_cpf").unmask();
        $(formIdentification + "filiation_2_cpf").unmask();
        $(
            formDocumentsAndAddress + "civil_register_enrollment_number"
        ).unmask();
        $(formDocumentsAndAddress + "cpf").unmask();
        $(formDocumentsAndAddress + "cep").unmask();
        $("#student input").removeAttr("disabled");
        $("#student select").removeAttr("disabled").trigger("change.select2");
        $(this).closest("form").submit();
    }
});
