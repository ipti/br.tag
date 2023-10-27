$(document).on("change", "#GradeUnity_edcenso_stage_vs_modality_fk", function () {
    $(".alert-required-fields, .alert-media-fields").hide();
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=admin/getUnities",
            cache: false,
            data: {
                stage: $("#GradeUnity_edcenso_stage_vs_modality_fk").val(),
            },
            beforeSend: function () {
                $(".js-grades-structure-loading").css("display", "inline-block");
                $(".js-grades-structure-container").css("pointer-events", "none").css("opacity", "0.4");
            },
            success: function (data) {
                data = JSON.parse(data);
                $(".stagemodalityname").text(data.stageName);
                $(".stagename").text($("#GradeUnity_edcenso_stage_vs_modality_fk").select2('data').text);
                $(".js-grades-structure-container").children(".unity").remove();
                $(".approval-media").val(data.approvalMedia);
                $(".final-recover-media").val(data.finalRecoverMedia);
                if (Object.keys(data.unities).length) {
                    $.each(data.unities, function () {
                        $(".js-new-unity").trigger("click");
                        var unity = $(".unity").last();
                        unity.find(".unity-name").val(this.name);
                        unity.find("select.type-select").val(this.type).trigger("change");
                        unity.find("select.formula-select").val(this.grade_calculation_fk).trigger("change");
                        var unityType = this.type;
                        $.each(this.modalities, function () {
                            var modality;
                            if (unityType === "UR") {
                                if (this.type === "C") {
                                    unity.find(".js-new-modality").trigger("click");
                                    modality = unity.find(".modality").last().prev();
                                } else {
                                    modality = unity.find(".modality").last();
                                }
                            } else if (unityType === "UC") {
                                modality = unity.find(".modality").last();
                            } else {
                                unity.find(".js-new-modality").trigger("click");
                                modality = unity.find(".modality").last();
                            }
                            modality.find(".modality-name").val(this.name);
                            modality.find(".weight").val(this.weight);
                        });
                    });
                }
                $(".grades-buttons").css("display", "flex");
                $(".js-grades-structure-container, .js-grades-rules-container").show();
                $(".js-grades-structure-loading").hide();
                $(".js-grades-structure-container").css("pointer-events", "auto").css("opacity", "1");
            },
        });
    } else {
        $(".js-grades-structure-container, .grades-buttons,  .js-grades-rules-container").hide();
    }
});

$(document).on("click", ".js-new-unity", function (evt) {
    var unityHtml = "" +
        "<div class='unity form-group form-inline'>" +
        "<label class='control-label required'>Nome: <span class='red'>*</span></label>" +
        "<input type='text' class='unity-name form-control' placeholder='1ª Unidade, 2ª Unidade, Recuperação Final, etc.'>" +
        "<i class='remove-unity fa fa-times-circle-o button-icon-close'></i>" +
        '<div class="unity-children">' +
        '<div class="unity-type form-group form-inline div-input-structure-units">' +
        "<label class='control-label required label-input-structure-units'>Modelo: <span class='red'>*</span></label>" +
        "<select class='type-select select-search-on control-input'><option value='U'>Unidade</option><option value='UR'>Unidade com recuperação</option><option value='UC'>Unidade por conceito</option><option value='RS'>Recuperação semestral</option><option value='RF'>Recuperação final</option></select>" +
        '</div>' +
        '<div class="calculation form-group form-inline div-input-structure-units">' +
        "<label class='control-label required label-input-structure-units'>Fórmula:  <span class='red'>*</span></label>" +
        "<select class='formula-select select-search-on control-input'>" + $(".formulas")[0].innerHTML + "</select>" +
        '</div>' +
        '<div class="row"><a href="#new-modality" id="new-modality" class="js-new-modality t-button-primary"><img alt="Unidade" src="/themes/default/img/buttonIcon/start.svg">Modalidade</a></div>' +
        '</div>' +
        '</div>';
    $(unityHtml).insertBefore($(this).parent());
    $(".unity").last().find(".type-select, .formula-select").select2();
});

$(document).on("change", ".type-select", function () {
    var unity = $(this).closest(".unity");
    if ($(this).val() === "UR") {
        unity.find(".js-new-modality").trigger("click").show();
        unity.find(".calculation").show();
        unity.find(".modality[concept=1]").remove();
        unity.find(".modality").last().children("label").html("Recuperação: " + '<span class="red">*</span>');
        unity.find(".modality").last().find(".modality-name").attr("modalitytype", "R").css("width", "calc(100% - 140px)");
        unity.find(".modality").last().find(".remove-modality, .weight").remove();
    } else if ($(this).val() === "UC") {
        unity.find(".modality").remove();
        unity.find(".js-new-modality").trigger("click").hide();
        unity.find(".formula-select").val("1").trigger("change");
        unity.find(".calculation").hide();
        unity.find(".modality-name[modalitytype=R]").closest(".modality").remove();
        unity.find(".modality").last().attr("concept", "1").find(".remove-modality").remove();
    } else {
        unity.find(".js-new-modality").show();
        unity.find(".calculation").show();
        unity.find(".modality-name[modalitytype=R]").closest(".modality").remove();
        unity.find(".modality[concept=1]").remove();
    }
});

$(document).on("change", ".formula-select", function () {
    var unity = $(this).closest(".unity");
    if ($(this).select2('data').text === "Peso") {
        unity.find(".modality-name[modalitytype=C]").css("width", "calc(100% - 240px)");
        unity.find(".modality-name[modalitytype=C]").parent().append("<input type='text' class='weight form-control' placeholder='Peso'>");
    } else {
        unity.find(".weight").remove();
        unity.find(".modality-name[modalitytype=C]").css("width", "calc(100% - 140px)");
    }
});

$(document).on("keyup", ".weight", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        var weight = /[1-9]|10/;
        if (val?.match(weight) === null) {
            val = "";
        } else {
            if (val > 10)
                val = 10;
        }
    }
    this.value = val;
});

$(document).on("click", ".js-new-modality", function (evt) {
    evt.preventDefault();
    var unityHtml = "";
    if ($(this).closest(".unity").find(".formula-select").select2('data').text === "Peso") {
        unityHtml += "" +
            "<div class='modality form-group form-inline' concept='0'>" +
            "<label class='control-label required'>Modalidade: <span class='red'>*</span></label>" +
            "<input type='text' class='modality-name form-control' modalitytype='C' placeholder='Prova, Avaliação, Trabalho, etc.' style='width: calc(100% - 222px);'>" +
            "<input type='text' class='weight form-control' placeholder='Peso'>" +
            "<i class='remove-modality fa fa-times-circle-o button-icon-close'></i>" +
            '</div>';
    } else {
        unityHtml += "" +
            "<div class='modality form-group form-inline' concept='0'>" +
            "<label class='control-label required'>Modalidade: <span class='red'>*</span></label>" +
            "<input type='text' class='modality-name form-control' modalitytype='C' placeholder='Prova, Avaliação, Trabalho, etc.'>" +
            "<i class='remove-modality fa fa-times-circle-o button-icon-close'></i>" +
            '</div>';
    }

    $(unityHtml).insertBefore(
        $(this).closest(".unity").find("select.type-select").val() !== "UR" || !$(this).closest(".unity").find(".modality").length
            ? $(this).parent()
            : $(this).closest(".unity").find(".modality").last());
});

$(document).on("click", ".remove-unity", function () {
    $(this).closest(".unity").remove();
});

$(document).on("click", ".remove-modality", function () {
    $(this).closest(".modality").remove();
});

$(document).on("click", ".save", function () {
    var valid = checkValidInputs();
    if (valid) {
        saveUnities(false);
    }
});

$(document).on("click", ".save-and-reply", function () {
    var valid = checkValidInputs();
    if (valid) {
        $("#js-saveandreply-modal").modal("show");
        $(".reply-option[value=S]").prop("checked", true);
    }
});

$(document).on("click", ".js-save-and-reply-button", function () {
    saveUnities(true);
});

function saveUnities(reply) {
    var unities = [];
    $(".unity").each(function () {
        var modalities = [];
        $(this).find(".modality").each(function () {
            modalities.push({
                name: $(this).find(".modality-name").val(),
                type: $(this).find(".modality-name").attr("modalitytype"),
                weight: $(this).find(".weight").length ? $(this).find(".weight").val() : null
            });
        });
        unities.push({
            name: $(this).find(".unity-name").val(),
            type: $(this).find("select.type-select").val(),
            formula: $(this).find("select.formula-select").val(),
            modalities: modalities
        })
    });
    $.ajax({
        type: "POST",
        url: "?r=admin/saveUnities",
        cache: false,
        data: {
            stage: $("#GradeUnity_edcenso_stage_vs_modality_fk").val(),
            unities: unities,
            approvalMedia: $(".approval-media").val(),
            finalRecoverMedia: $(".final-recover-media").val(),
            reply: reply ? $(".reply-option:checked").val() : ""
        },
        beforeSend: function () {
            $(".alert-media-fields").addClass("alert-warning").removeClass("alert-success").text("Atualizando resultados dos alunos, o processo pode demorar...").show();
            $(".buttons a, .js-grades-structure-container").css("opacity", "0.4").css("pointer-events", "none");
            $("#GradeUnity_edcenso_stage_vs_modality_fk").attr("disabled", "disabled");
            $(".save-unity-loading-gif").css("display", "inline-block");
        },
        success: function (data) {
            data = JSON.parse(data);
            if (data.valid) {
                $(".alert-required-fields").addClass("alert-success").removeClass("alert-error").text("Estrutura de notas cadastrada com sucesso!").show();
            } else {
                $(".alert-required-fields").addClass("alert-error").removeClass("alert-success").text("Não se pode alterar a estrutura quando já existe nota preechida em alguma turma desta etapa e disciplina.").show();
            }
            $(".alert-media-fields").removeClass("alert-warning").addClass("alert-success").text("Médias atualizadas com sucesso!");
            $("html, body").animate({scrollTop: 0}, "fast");
            $(".buttons a, .js-grades-structure-container").css("opacity", "1").css("pointer-events", "auto");
            $("#GradeUnity_edcenso_stage_vs_modality_fk").removeAttr("disabled");
            $(".save-unity-loading-gif").hide();
        },
    });
}

function checkValidInputs() {
    $(".alert-required-fields, .alert-media-fields").hide();
    var valid = true;
    var message = "";
    if ($(".approval-media").val() === "" || $(".final-recover-media").val() === "") {
        valid = false;
        message = "Os campos de média são obrigatórios.";
    } else if ($(".approval-media").val() < $(".final-recover-media").val()) {
        valid = false;
        message = "A média de recuperação final não pode ser superior à de aprovação.";
    }
    if (valid) {
        if ($(".unity").length) {
            var ucCount = 0;
            var rsCount = 0;
            var rsIndexes = [];
            $(".unity").each(function (index) {
                if ($(this).find(".unity-name").val() === "") {
                    valid = false;
                    message = "Preencha o nome das unidades.";
                    return false;
                }
                $(this).find(".modality").each(function () {
                    if ($(this).find(".modality-name").val() === "") {
                        valid = false;
                        message = "Preencha o nome das modalidades.";
                        return false;
                    }
                    if ($(this).find(".weight").val() === "") {
                        valid = false;
                        message = "Preencha o peso das modalidades.";
                        return false;
                    }
                });
                if ($(this).find("select.type-select").val() === "UC") {
                    ucCount++;
                }
                if ($(this).find("select.type-select").val() === "UR") {
                    if (!$(this).find(".modality-name[modalitytype=C]").length) {
                        valid = false;
                        message = 'Unidades do modelo "Unidade com recuperação" requer duas ou mais modalidades.';
                        return false;
                    }
                } else {
                    if (!$(this).find(".modality-name").length) {
                        valid = false;
                        message = "Não se pode cadastrar unidades sem modalidade.";
                        return false;
                    }
                }
                if (index === 0 && ($(this).find("select.type-select").val() === "RF" || $(this).find("select.type-select").val() === "RS")) {
                    valid = false;
                    message = "Uma unidade de recuperação semestral ou final não podem ser a primeira.";
                    return false;
                }
                if ($(this).find("select.type-select").val() === "RF" && index !== $(".unity").length - 1) {
                    valid = false;
                    message = "A unidade de recuperação final, quando utilizada, deve haver apenas 01, sendo a última unidade.";
                    return false;
                }
                if (rsCount === 2 && $(this).find("select.type-select").val() !== "RF") {
                    valid = false;
                    message = "Não pode haver unidades após a 2ª recuperação semestral.";
                    return false;
                }
                if ($(this).find("select.type-select").val() === "RS") {
                    rsCount++;
                    rsIndexes.push(index);
                }
            });
            if (rsIndexes.length && rsIndexes[1] - rsIndexes[0] === 1) {
                valid = false;
                message = "Não pode haver 02 recuperações semestrais seguidas.";
            }
            if (rsCount !== 0 && rsCount !== 2) {
                valid = false;
                message = "Quando utilizadas, devem haver 02 recuperações semestrais.";
            }
            if (ucCount > 0 && ucCount !== $(".unity").length) {
                valid = false;
                message = "Quando uma unidade por conceito for utilizada, nenhum outro modelo pode ser utilizado.";
            }
        } else {
            valid = false;
            message = "Não se pode cadastrar uma estrutura de notas sem unidade.";
        }
    }

    if (!valid) {
        $(".alert-required-fields").addClass("alert-error").removeClass("alert-success").text(message).show();
        $("html, body").animate({scrollTop: 0}, "fast");
    }
    return valid;
}

$(document).on("keyup", ".approval-media, .final-recover-media", function (e) {
    var val = this.value;
    if (!$.isNumeric(val)) {
        e.preventDefault();
        val = "";
    } else {
        grade = /^(10|\d)(?:(\.|\,)\d{0,1}){0,1}$/;
        if (val?.match(grade) === null) {
            val = "";
        } else {
            if (val > 10) {
                val = 10;
            } else if (val == 0) {
                val = "";
            }

        }
    }
    this.value = val;
});