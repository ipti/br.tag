function validateSave() {
    var submit = true;
    var name = "#CoursePlan_name";
    var stage = "#CoursePlan_modality_fk";
    var disciplines = "#CoursePlan_discipline_fk";
    if ($(name).val() === "") {
        submit = false;
        addError(name, "Campo 'Nome' obrigatório.");
    } else {
        removeError(name);
    }
    if ($(stage).val() === "") {
        submit = false;
        addError(stage, "Campo 'Etapa' obrigatório.");
    } else {
        removeError(stage);
    }
    if ($(disciplines).val() === "") {
        submit = false;
        addError(disciplines, "Campo 'Disciplinas' obrigatório.");
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

$("#CoursePlan_name").focusout(function(){
    var id = $(this).attr("id");
    if ($(this).val().length >= 3){
        removeError("#"+id);
    } else {
        addError("#"+id, "Campo 'Nome' precisa ter pelo menos 3 caracteres.");
    }
});

$("#CoursePlan_name").keyup(function(){
    var id = $(this).attr("id");
    if ($(this).val().length >= 3){
        removeError("#"+id);
    }
});

$("#CoursePlan_modality_fk").change(function(){
    var id = $(this).attr("id");
    if ($(this).val() !== ""){
        removeError("#"+id);
    } else {
        addError("#"+id, "Selecione uma etapa.");
    }
});

$("#CoursePlan_discipline_fk").change(function(){
    var id = $(this).attr("id");
    if ($(this).val() !== ""){
        removeError("#"+id);
    } else {
        addError("#"+id, "Selecione uma disciplina.");
    }
});

$(document).on("focusout", ".course-class-objective", function(){
    var id = $(this).attr("id");
    if ($(this).val().length >= 3){
        removeError("#"+id);
    } else {
        addError("#"+id, "Campo 'Objetivo' precisa ter pelo menos 3 caracteres.");
    }
});

$(document).on("keyup", ".course-class-objective", function(){
    var id = $(this).attr("id");
    if ($(this).val().length >= 3){
        removeError("#"+id);
    }
});