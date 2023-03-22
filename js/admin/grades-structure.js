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
                $(".grades-structure-container").css("pointer-events", "none").css("opacity", "0.4");
            },
            success: function (data) {
                data = JSON.parse(data);
                if (Object.keys(data.unities).length) {

                } else {

                }
                $(".grades-structure-container").show();
                $(".js-grades-structure-loading").hide();
                $(".grades-structure-container").css("pointer-events", "auto").css("opacity", "1");
            },
        });
    } else {
        $(".grades-structure-container").hide();
    }
});