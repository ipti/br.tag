/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).on("click", ".add-fundamental-menor", function () {
    $("#SchoolStructure_stages_concept_grades option[value=14]").prop("selected", true);
    $("#SchoolStructure_stages_concept_grades option[value=15]").prop("selected", true);
    $("#SchoolStructure_stages_concept_grades option[value=16]").prop("selected", true);
    $("#SchoolStructure_stages_concept_grades").trigger("change.select2");
});

$(document).on("change", "#SchoolIdentification_administrative_dependence", function() {
    if ($(this).val() !== "" && $(this).val() !== "4") {
        $("#SchoolIdentification_linked_organ").parent().children(".control-label").addClass("required").html("Órgão ao qual a escola pública está vinculada *");
        $("#SchoolIdentification_linked_organ input[type=checkbox]").removeAttr("disabled", "disabled");
    } else {
        $("#SchoolIdentification_linked_organ").parent().children(".control-label").removeClass("required").html("Órgão ao qual a escola pública está vinculada");
        if ($(this).val() === "4") {
            $("#SchoolIdentification_linked_organ input[type=checkbox]:checked").prop("checked", false);
            $("#SchoolIdentification_linked_organ input[type=checkbox]").attr("disabled", "disabled");
        }
    }
});
$("#SchoolIdentification_administrative_dependence").trigger("change");