function validateSave() {
    var submit = true;
    var name = "#CoursePlan_name";
    var stage = "#CoursePlan_modality_fk";
    var disciplines = "#CoursePlan_discipline_fk";
    var minorEducationDisciplines = "#minorEducationDisciplines";
    let url = new URL(window.location.href);
    let urlId = url.searchParams.get("id");

    if ($(name).val() === "" || $(name).val().length < 3) {
        submit = false;
        addError(name, "Campo obrigatório (mínimo de 03 caracteres).");
    } else {
        removeError(name);
    }
    if ($(stage).val() === "") {
        submit = false;
        addError(stage, "Campo obrigatório.");
    } else {
        removeError(stage);
    }
    if (
        $(disciplines).val() === "" &&
        isStageChildishEducation($(stage).val()) == false
    ) {
        submit = false;
        addError(disciplines, "Campo obrigatório.");
    } else {
        removeError(disciplines);
    }
    $.each($(".course-class-objective"), function () {
        var objective = $(this).attr("id");
        var tr = $(this).closest("tr");
        if ($(this).val() === "") {
            if (tr.css("display") === "none") {
                tr.prev().find(".details-control").click();
            }
            submit = false;
            addError("#" + objective, "Campo 'Objetivo' obrigatório.");
        } else {
            if (tr.css("display") !== "none") {
                tr.prev().find(".details-control").click();
            }
            removeError("#" + objective);
        }
    });
    return submit;
}

$(document).on("focusout", ".course-class-objective", function () {
    var id = $(this).attr("id");
    if ($(this).val().length >= 3) {
        removeError("#" + id);
    } else {
        addError(
            "#" + id,
            "Campo 'Objetivo' precisa ter pelo menos 3 caracteres."
        );
    }
});

$(document).on("keyup", ".course-class-objective", function () {
    var id = $(this).attr("id");
    if ($(this).val().length >= 3) {
        removeError("#" + id);
    }
});

$("#courseplan_start_date").mask("99/99/9999", { placeholder: "DD/MM/YYYY" });
$("#courseplan_start_date").focusout(function () {
    let id = "#" + $(this).attr("id");
    let date = new Date();
    let actualYear = date.getFullYear();
    let initialDate = stringToDate($("#courseplan_start_date").val());
    if (
        !validateDate($("#courseplan_start_date").val()) ||
        !(initialDate.year >= actualYear - 1 && initialDate.year <= actualYear)
    ) {
        $("#courseplan_start_date").attr("value", "");
        addError(
            id,
            "A data deve ser válida, no formato Dia/Mês/Ano e inferior a data final."
        );
    } else {
        removeError(id);
    }
});

function isStageChildishEducation(stage) {
    const refMinorStages = ["1", "2", "3"];
    return refMinorStages.includes(stage);
}
