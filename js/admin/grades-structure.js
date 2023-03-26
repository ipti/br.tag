$(document).on("change", "#GradeUnity_edcenso_stage_vs_modality_fk", function (evt, loadingData) {
    $("#GradeUnity_edcenso_discipline_fk").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=admin/getDisciplines",
            cache: false,
            data: {
                stage: $("#GradeUnity_edcenso_stage_vs_modality_fk").val(),
            },
            beforeSend: function () {
                $(".js-grades-structure-loading").css("display", "inline-block");
                $("#GradeUnity_edcenso_discipline_fk").attr("disabled", "disabled");
            },
            success: function (data) {
                data = JSON.parse(data);
                $(".stagemodalityname").text(data.stageName);
                $(".stagename").text($("#GradeUnity_edcenso_stage_vs_modality_fk").select2('data').text);
                var option = "<option value=''>Selecione a disciplina...</option>";
                $.each(data["disciplines"], function () {
                    var selectedValue = loadingData !== undefined && $("#GradeUnity_edcenso_discipline_fk").attr("initval") !== "" && $("#GradeUnity_edcenso_discipline_fk").attr("initval") === this.id ? "selected" : "";
                    option += "<option value='" + this.id + "' " + selectedValue + ">" + this.name + "</option>";
                });
                $("#GradeUnity_edcenso_discipline_fk").html(option).trigger("change").show();
                $(".js-grades-structure-loading").hide();
                $("#GradeUnity_edcenso_discipline_fk").removeAttr("disabled");
            },
        });
    } else {
        $("#GradeUnity_edcenso_discipline_fk").html("<option value=''>Selecione a disciplina...</option>").trigger("change").show();
    }
});
$("#GradeUnity_edcenso_stage_vs_modality_fk").trigger("change", [true]);

$(document).on("change", "#GradeUnity_edcenso_discipline_fk", function () {
    $(".alert-required-fields").hide();
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=admin/getUnities",
            cache: false,
            data: {
                stage: $("#GradeUnity_edcenso_stage_vs_modality_fk").val(),
                discipline: $("#GradeUnity_edcenso_discipline_fk").val(),
            },
            beforeSend: function () {
                $(".js-grades-structure-loading").css("display", "inline-block");
                $(".js-grades-structure-container").css("pointer-events", "none").css("opacity", "0.4");
            },
            success: function (data) {
                data = JSON.parse(data);
                $(".js-grades-structure-container").children(".unity").remove();
                if (Object.keys(data.unities).length) {
                    $.each(data.unities, function () {
                        $(".js-new-unity").trigger("click");
                        var unity = $(".unity").last();
                        unity.find(".unity-name").val(this.name);
                        unity.find("select.type-select").val(this.type).trigger("change.select2");
                        unity.find("select.formula-select").val(this.grade_calculation_fk).trigger("change.select2");
                        $.each(this.modalities, function () {
                            unity.find(".js-new-modality").trigger("click");
                            unity.find(".modality").last().find(".modality-name").val(this.name);
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

$(document).on("click", ".js-new-unity", function () {
    var unityHtml = "" +
        "<div class='unity form-group form-inline'>" +
        "<label class='control-label'>Nome: <span class='red'>*</span></label>" +
        "<input type='text' class='unity-name form-control' placeholder='1ª Unidade, 2ª Unidade, Recuperação Final, etc.'>" +
        "<i class='remove-unity fa fa-times-circle-o'></i>" +
        '<div class="unity-children">' +
        '<div class="unity-type form-group form-inline">' +
        "<label class='control-label'>Tipo: <span class='red'>*</span></label>" +
        "<select class='type-select select-search-on control-input'><option value='C'>Unidade</option><option value='R'>Recuperação</option></select>" +
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

$(document).on("click", ".js-new-modality", function () {
    var unityHtml = "" +
        "<div class='modality form-group form-inline'>" +
        "<label class='control-label'>Modalidade: <span class='red'>*</span></label>" +
        "<input type='text' class='modality-name form-control' placeholder='Prova, Avaliação, etc.'>" +
        "<i class='remove-modality fa fa-times-circle-o'></i>" +
        '</div>';
    $(unityHtml).insertBefore($(this).parent());
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
            modalities.push($(this).val());
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
            discipline: $("#GradeUnity_edcenso_discipline_fk").val(),
            unities: unities,
            reply: reply ? $(".reply-option:checked").val() : ""
        },
        beforeSend: function () {
            $(".buttons a, .js-grades-structure-container").css("opacity", "0.4").css("pointer-events", "none");
            $("#GradeUnity_edcenso_stage_vs_modality_fk, #GradeUnity_edcenso_discipline_fk").attr("disabled", "disabled");
            $(".save-unity-loading-gif").css("display", "inline-block");
        },
        success: function (data) {
            data = JSON.parse(data);
            if (data.valid) {
                $(".alert-required-fields").addClass("alert-success").removeClass("alert-error").text("Estrutura de notas cadastrada com sucesso!").show();
            } else {
                $(".alert-required-fields").addClass("alert-error").removeClass("alert-success").text("Não se pode alterar a estrutura quando já existe nota cadastrada em alguma turma.").show();
            }
            $(".buttons a, .js-grades-structure-container").css("opacity", "1").css("pointer-events", "auto");
            $("#GradeUnity_edcenso_stage_vs_modality_fk, #GradeUnity_edcenso_discipline_fk").removeAttr("disabled");
            $(".save-unity-loading-gif").hide();
        },
    });
}

function checkValidInputs() {
    $(".alert-required-fields").hide();
    var valid = true;
    var message = "";
    if ($(".unity").length) {
        $(".unity").each(function () {
            if ($(this).find(".unity-name, .modality-name").val() === "") {
                valid = false;
                message = "Campos com * são obrigatórios.";
                return false;
            }
            if (!$(this).find(".modality-name").length) {
                valid = false;
                message = "Não se pode cadastrar unidades sem modalidade.";
                return false;
            }
        });
    } else {
        valid = false;
        message = "Não se pode cadastrar uma estrutura de notas sem unidade.";
    }
    if (!valid) {
        $(".alert-required-fields").addClass("alert-error").removeClass("alert-success").text(message).show();
    }
    return valid;
}