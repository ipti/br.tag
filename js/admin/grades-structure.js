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
                var option = "<option value=''>Selecione a disciplina...</option>";
                $.each(data, function () {
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

$(document).on("click", ".remove-unity", function() {
    $(this).closest(".unity").remove();
});

$(document).on("click", ".remove-modality", function() {
    $(this).closest(".modality").remove();
});

$(document).on("click", ".save", function() {
    var valid = checkValidInputs();
    if (valid) {
        $.ajax({
            type: "POST",
            url: "?r=admin/saveUnities",
            cache: false,
            data: {

            },
            beforeSend: function () {

            },
            success: function (data) {
                data = JSON.parse(data);

            },
        });
    }
});

$(document).on("click", ".save-and-reply", function() {
    var valid = checkValidInputs();
});

function checkValidInputs() {
    $(".alert-required-fields").hide();
    var valid = true;
    var message = "";
    if ($(".unity").length) {
        $(".unity").each(function() {
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
        $(".alert-required-fields").text(message).show();
    }
    return valid;
}