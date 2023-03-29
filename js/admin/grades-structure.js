$(document).on("change", "#GradeUnity_edcenso_stage_vs_modality_fk", function () {
    $(".alert-required-fields").hide();
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
                if (Object.keys(data.unities).length) {
                    $.each(data.unities, function () {
                        $(".js-new-unity").trigger("click");
                        var unity = $(".unity").last();
                        unity.find(".unity-name").val(this.name);
                        unity.find("select.type-select").val(this.type).trigger("change.select2");
                        unity.find("select.formula-select").val(this.grade_calculation_fk).trigger("change.select2");
                        $.each(this.modalities, function () {
                            unity.find(".js-new-modality").trigger("click", [true]);
                            unity.find(".modality").last().find(".modality-name").val(this.name);
                            if (this.type === "R") {
                                changeRecoverModality(unity);
                            }
                        });
                    });
                }
                $(".grades-buttons").css("display", "flex");
                $(".js-grades-structure-container").show();
                $(".js-grades-structure-loading").hide();
                $(".js-grades-structure-container").css("pointer-events", "auto").css("opacity", "1");
            },
        });
    } else {
        $(".js-grades-structure-container, .grades-buttons").hide();
    }
});

$(document).on("click", ".js-new-unity", function (evt) {
    var unityHtml = "" +
        "<div class='unity form-group form-inline'>" +
        "<label class='control-label'>Nome: <span class='red'>*</span></label>" +
        "<input type='text' class='unity-name form-control' placeholder='1ª Unidade, 2ª Unidade, Recuperação Final, etc.'>" +
        "<i class='remove-unity fa fa-times-circle-o'></i>" +
        '<div class="unity-children">' +
        '<div class="unity-type form-group form-inline">' +
        "<label class='control-label'>Modelo: <span class='red'>*</span></label>" +
        "<select class='type-select select-search-on control-input'><option value='U'>Unidade</option><option value='UR'>Unidade com recuperação</option><option value='RS'>Recuperação semestral</option><option value='RF'>Recuperação final</option></select>" +
        '</div>' +
        '<div class="calculation form-group form-inline">' +
        "<label class='control-label'>Fórmula: <span class='red'>*</span></label>" +
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
        unity.find(".js-new-modality").trigger("click", [true]);
        changeRecoverModality(unity);

    } else {
        unity.find(".modality-name[modalitytype=R]").closest(".modality").remove();
    }
});

function changeRecoverModality(unity) {
    unity.find(".modality").last().children("label").html("Recuperação: " + '<span class="red">*</span>');
    unity.find(".modality").last().find(".modality-name").attr("modalitytype", "R");
    unity.find(".modality").last().find(".remove-modality").remove();
}

$(document).on("click", ".js-new-modality", function (evt, indirectTrigger) {
    evt.preventDefault();
    var unityHtml = "" +
        "<div class='modality form-group form-inline'>" +
        "<label class='control-label'>Modalidade: <span class='red'>*</span></label>" +
        "<input type='text' class='modality-name form-control' modalitytype='C' placeholder='Prova, Avaliação, Trabalho, etc.'>" +
        "<i class='remove-modality fa fa-times-circle-o'></i>" +
        '</div>';
    $(unityHtml).insertBefore(
        $(this).closest(".unity").find("select.type-select").val() !== "UR" || indirectTrigger
            ? $(this).parent()
            : $(this).closest(".unity").find(".modality").last()
    );
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
        $(this).find(".modality-name").each(function () {
            modalities.push({
                name: $(this).val(),
                type: $(this).attr("modalitytype")
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
            reply: reply ? $(".reply-option:checked").val() : ""
        },
        beforeSend: function () {
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
            $("html, body").animate({scrollTop: 0}, "fast");
            $(".buttons a, .js-grades-structure-container").css("opacity", "1").css("pointer-events", "auto");
            $("#GradeUnity_edcenso_stage_vs_modality_fk").removeAttr("disabled");
            $(".save-unity-loading-gif").hide();
        },
    });
}

function checkValidInputs() {
    $(".alert-required-fields").hide();
    var valid = true;
    var message = "";
    if ($(".unity").length) {
        $(".unity").each(function (index) {
            if ($(this).find(".unity-name, .modality-name").val() === "") {
                valid = false;
                message = "Campos com * são obrigatórios.";
                return false;
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
            if ($(".unity").length === 1 && $(this).find("select.type-select").val() === "RF") {
                valid = false;
                message = "Não se pode cadastrar uma estrutura apenas com a unidade de recuperação final.";
                return false;
            }
            if ($(this).find("select.type-select").val() === "RF" && index !== $(".unity").length - 1) {
                valid = false;
                message = "A unidade de recuperação final deve ser a última.";
                return false;
            }
        });
    } else {
        valid = false;
        message = "Não se pode cadastrar uma estrutura de notas sem unidade.";
    }
    if (!valid) {
        $(".alert-required-fields").addClass("alert-error").removeClass("alert-success").text(message).show();
        $("html, body").animate({scrollTop: 0}, "fast");
    }
    return valid;
}