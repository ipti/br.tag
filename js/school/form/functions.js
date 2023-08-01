/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$('#ManagerIdentification_nationality').change(function () {
    var nationality = ".nationality-sensitive";
    var br = nationality + ".br";
    var nobr = nationality + ".no-br";
    var simple = getUrlVars()['simple'];
    $(nationality).attr("disabled", "disabled");
    if ($(this).val() == 3) {
        $(nobr).removeAttr("disabled");
        $('#ManagerIdentification_edcenso_nation_fk').val(null).trigger('change').select2('readonly', false);
        $('#ManagerIdentification_edcenso_uf_fk').val("").trigger("change.select2");
        $('#ManagerIdentification_edcenso_city_fk').val("").trigger("change.select2");
        $('#ManagerIdentification_edcenso_uf_fk').closest(".js-change-required").css("display", simple === "1" ? "none" : "block").find("label").removeClass("required").html("Estado");
        $('#ManagerIdentification_edcenso_city_fk').closest(".js-change-required").css("display", simple === "1" ? "none" : "block").find("label").removeClass("required").html("Cidade");
    } else if ($(this).val() == "") {
        $('#ManagerIdentification_edcenso_nation_fk').val(null).trigger('change').attr("disabled", "disabled");
        $(nationality).attr("disabled", "disabled");
        $('#ManagerIdentification_edcenso_uf_fk').val("").trigger("change.select2").closest(".js-change-required").css("display", simple == "1" ? "none" : "block").find("label").removeClass("required").html("Estado");
        $('#ManagerIdentification_edcenso_city_fk').val("").trigger("change.select2").closest(".js-change-required").css("display", simple == "1" ? "none" : "block").find("label").removeClass("required").html("Cidade");
    } else {
        $('#ManagerIdentification_edcenso_nation_fk').val(76).trigger('change').removeAttr("disabled").select2('readonly', true);
        $(br).removeAttr("disabled");
        if ($(this).val() == "1") {
            $('#ManagerIdentification_edcenso_uf_fk').removeAttr("disabled").closest(".js-change-required").show().find("label").addClass("required").html("Estado *");
            $('#ManagerIdentification_edcenso_city_fk').removeAttr("disabled").closest(".js-change-required").show().find("label").addClass("required").html("Cidade *");
        } else {
            $('#ManagerIdentification_edcenso_uf_fk').val("").trigger("change.select2").attr("disabled", "disabled").closest(".js-change-required").css("display", simple == "1" ? "none" : "block").find("label").removeClass("required").html("Estado");
            $('#ManagerIdentification_edcenso_city_fk').val("").trigger("change.select2").attr("disabled", "disabled").closest(".js-change-required").css("display", simple == "1" ? "none" : "block").find("label").removeClass("required").html("Cidade");
        }
    }
});

$('#ManagerIdentification_edcenso_uf_fk').change(function () {
    var uf = $(this).val();
    $("#ManagerIdentification_edcenso_city_fk").empty();
    $.ajax({
        type: "POST",
        url: `?r=school/getmanagercities`,
        data: {
            edcenso_uf_fk: uf
        },
        success: function (response) {
            $("#ManagerIdentification_edcenso_city_fk").append(response);
        }
    });
});

$('#ManagerIdentification_filiation').change(function () {
    var simple = getUrlVars()['simple'];
    $('.manager-filiation-container').hide();
    if ($('#ManagerIdentification_filiation').val() == 1) {
        $('.manager-filiation-container').show();
        $('#ManagerIdentification_filiation_1').closest(".js-visibility-fname").show();
        $('#ManagerIdentification_filiation_2').closest(".js-visibility-fname").show();
    } else {
        $('.js-finput-clear').val("")
        $('#ManagerIdentification_filiation_1').closest(".js-visibility-fname").css("display", simple === "1" ? "none" : "block");
        $('#ManagerIdentification_filiation_2').closest(".js-visibility-fname").css("display", simple === "1" ? "none" : "block");

    }
});
$('#ManagerIdentification_filiation').trigger('change');


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

$(document).on("change", "#SchoolIdentification_offer_or_linked_unity", function () {
    if ($(this).val() === "1") {
        $("#SchoolIdentification_inep_head_school").removeAttr("disabled").closest(".control-group").find(".control-label").addClass("required").html("Código da Escola Sede *");
        $("#SchoolIdentification_ies_code").val("").attr("disabled", "disabled").closest(".control-group").find(".control-label").removeClass("required").html("Código da IES");
    } else if ($(this).val() === "2") {
        $("#SchoolIdentification_inep_head_school").val("").attr("disabled", "disabled").closest(".control-group").find(".control-label").removeClass("required").html("Código da Escola Sede");
        $("#SchoolIdentification_ies_code").removeAttr("disabled").closest(".control-group").find(".control-label").addClass("required").html("Código da IES *");
    } else {
        $("#SchoolIdentification_inep_head_school").val("").attr("disabled", "disabled").closest(".control-group").find(".control-label").removeClass("required").html("Código da Escola Sede");
        $("#SchoolIdentification_ies_code").val("").attr("disabled", "disabled").closest(".control-group").find(".control-label").removeClass("required").html("Código da IES");
    }
});
$("#SchoolIdentification_offer_or_linked_unity").trigger("change");

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
    if ($(this).is(":checked")) {
        if ($(this).attr("id") !== "SchoolStructure_internet_access_inexistent") {
            $("#SchoolStructure_internet_access_inexistent").prop("checked", false);
        } else {
            $("#SchoolStructure_internet_access_broadband").prop("checked", false).attr("disabled", "disabled");
            $(".internet-access-container input[type=checkbox]").not("#SchoolStructure_internet_access_inexistent").prop("checked", false);
        }
    }
    if (!$("#SchoolStructure_internet_access_inexistent").is(":checked")) {
        $("#SchoolStructure_internet_access_broadband").removeAttr("disabled");
    }
});
$("#SchoolStructure_internet_access_inexistent").trigger("change");

$(document).on("change", "#SchoolStructure_internet_access_local_wireless", function () {
    if (!$(this).is(":checked")) {
        $("#SchoolStructure_internet_access_connected_personaldevice").prop("checked", false).attr("disabled", "disabled");
    } else {
        $("#SchoolStructure_internet_access_connected_personaldevice").removeAttr("disabled");
    }
});
$("#SchoolStructure_internet_access_local_wireless").trigger("change");

$(document).on("change", ".internet-access-local-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_internet_access_local_inexistet") {
        $("#SchoolStructure_internet_access_local_inexistet").prop("checked", false);
    } else {
        $(".internet-access-local-container input[type=checkbox]").not("#SchoolStructure_internet_access_local_inexistet").prop("checked", false);
    }
});

$(document).on("change", ".equipments-material-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_instruments_inexistent") {
        $("#SchoolStructure_instruments_inexistent").prop("checked", false);
    } else {
        $(".equipments-material-container input[type=checkbox]").not("#SchoolStructure_instruments_inexistent").prop("checked", false);
    }
});

$(document).on("change", "#SchoolStructure_select_adimission", function () {
    if ($(this).is(":checked")) {
        $(".booking-container input[type=checkbox]").removeAttr("disabled");
        $(".booking-container").find(".control-label").addClass("required").html("Reserva de vagas por sistema de cotas *");
    } else {
        $(".booking-container input[type=checkbox]").prop("checked", false).attr("disabled", "disabled");
        $(".booking-container").find(".control-label").removeClass("required").html("Reserva de vagas por sistema de cotas");
    }
});
$("#SchoolStructure_select_adimission").trigger("change");

$(document).on("change", ".booking-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_booking_enrollment_inexistent") {
        $("#SchoolStructure_booking_enrollment_inexistent").prop("checked", false);
    } else {
        $(".booking-container input[type=checkbox]").not("#SchoolStructure_booking_enrollment_inexistent").prop("checked", false);
    }
});

$(document).on("change", ".board-organ-container input[type=checkbox]", function () {
    if ($(this).attr("id") !== "SchoolStructure_board_organ_inexistent") {
        $("#SchoolStructure_board_organ_inexistent").prop("checked", false);
    } else {
        $(".board-organ-container input[type=checkbox]").not("#SchoolStructure_board_organ_inexistent").prop("checked", false);
    }
});