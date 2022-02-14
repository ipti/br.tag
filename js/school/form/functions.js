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

$(document).on("change", "#SchoolIdentification_administrative_dependence", function () {
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

$(document).on("change", "#SchoolStructure_shared_building_with_school", function () {
    $(this).is(":checked")
        ? $("#SchoolStructure_shared_school_inep_id_1").removeAttr("disabled").trigger("change.select2")
        : $("#SchoolStructure_shared_school_inep_id_1").val("").attr("disabled", "disabled").trigger("change.select2");
});

$(document).on("change", "#SchoolStructure_operation_location_building", function () {
    if ($(this).is(":checked")) {
        $("#SchoolStructure_building_occupation_situation").closest(".control-group").find(".control-label").addClass("required").html("Forma de Ocupação do Prédio *");
        $("#SchoolStructure_building_occupation_situation").removeAttr("disabled").trigger("change.select2");
        $("#SchoolStructure_classroom_count").closest(".control-group").find(".control-label").addClass("required").html("Nº de Salas de Aula *");
        $("#SchoolStructure_classroom_count").removeAttr("disabled");
        $("#SchoolStructure_dependencies_outside_roomspublic").closest(".control-group").find(".control-label").removeClass("required").html("Nº de Salas utilizadas fora do prédio");
        $("#SchoolStructure_shared_building_with_school").removeAttr("disabled").trigger("change");
    } else {
        $("#SchoolStructure_building_occupation_situation").closest(".control-group").find(".control-label").removeClass("required").html("Forma de Ocupação do Prédio");
        $("#SchoolStructure_building_occupation_situation").val("").attr("disabled", "disabled").trigger("change.select2");
        $("#SchoolStructure_classroom_count").closest(".control-group").find(".control-label").removeClass("required").html("Nº de Salas de Aula");
        $("#SchoolStructure_classroom_count").val("").attr("disabled", "disabled");
        $("#SchoolStructure_dependencies_outside_roomspublic").closest(".control-group").find(".control-label").addClass("required").html("Nº de Salas utilizadas fora do prédio *");
        $("#SchoolStructure_shared_building_with_school").prop("checked", false).attr("disabled", "disabled").trigger("change");
    }
});
$("#SchoolStructure_operation_location_building").trigger("change");

$(document).on("change", ".water-supply-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_water_supply_inexistent") {
        $("#SchoolStructure_water_supply_inexistent").prop("checked", false);
    } else {
        $(".water-supply-container input[type=checkbox]").not("#SchoolStructure_water_supply_inexistent").prop("checked", false);
    }
});

$(document).on("change", ".energy-supply-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_energy_supply_inexistent") {
        $("#SchoolStructure_energy_supply_inexistent").prop("checked", false);
    } else {
        $(".energy-supply-container input[type=checkbox]").not("#SchoolStructure_energy_supply_inexistent").prop("checked", false);
    }
});

$(document).on("change", ".sewage-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_sewage_inexistent") {
        $("#SchoolStructure_sewage_inexistent").prop("checked", false);
    } else {
        $(".sewage-container input[type=checkbox]").not("#SchoolStructure_sewage_inexistent").prop("checked", false);
    }
    if ($(this).attr("id") === "SchoolStructure_sewage_fossa_common") {
        $("#SchoolStructure_sewage_fossa").prop("checked", false);
    } else {
        $("#SchoolStructure_sewage_fossa_common").prop("checked", false);
    }
});

$(document).on("change", ".garbage-treatment-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_traetment_garbage_inexistent") {
        $("#SchoolStructure_traetment_garbage_inexistent").prop("checked", false);
    } else {
        $(".garbage-treatment-container input[type=checkbox]").not("#SchoolStructure_traetment_garbage_inexistent").prop("checked", false);
    }
});

$(document).on("change", ".dependencies-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_dependencies_none") {
        $("#SchoolStructure_dependencies_none").prop("checked", false);
    } else {
        $(".dependencies-container input[type=checkbox]").not("#SchoolStructure_dependencies_none").prop("checked", false);
    }
});

$(document).on("change", ".accessbility-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_acessabilty_inexistent") {
        $("#SchoolStructure_acessabilty_inexistent").prop("checked", false);
    } else {
        $(".accessbility-container input[type=checkbox]").not("#SchoolStructure_acessabilty_inexistent").prop("checked", false);
    }
});

$(document).on("change", ".equipments-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_equipments_inexistent") {
        $("#SchoolStructure_equipments_inexistent").prop("checked", false);
    } else {
        $(".equipments-container input[type=checkbox]").not("#SchoolStructure_equipments_inexistent").prop("checked", false);
    }
});

$(document).on("change", ".internet-access-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_internet_access_inexistent") {
        $("#SchoolStructure_internet_access_inexistent").prop("checked", false);
    } else {
        $(".internet-access-container input[type=checkbox]").not("#SchoolStructure_internet_access_inexistent").prop("checked", false);
    }
});