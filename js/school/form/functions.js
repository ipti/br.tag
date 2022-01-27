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

