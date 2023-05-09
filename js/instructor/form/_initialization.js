window.location.search.includes("update") ? $('.last').css('display', 'block') : $('.last').css('display', 'none');

 $('.heading-buttons').css('width', $('#content').width());

 $(window).load(function () {
    $(formInstructorvariableData + 'scholarity').trigger('change');
    $(formInstructorIdentification + 'birthday_date').mask("99/99/9999");
    $(formInstructorvariableData + "high_education_institution_code_1_fk, "
        + formInstructorvariableData + "high_education_institution_code_2_fk, "
        + formInstructorvariableData + "high_education_institution_code_3_fk").select2({
        placeholder: "Selecione a Instituição",
        minimumInputLength: 3,
        ajax: {
            type: 'POST',
            url: GET_INSTITUTIONS,
            dataType: 'json',
            quietMillis: 500,
            data: function (term, page) { // page is the one-based page number tracked by Select2
                return {
                    q: term, //search term
                    page: page // page number
                };
            },
            results: function (data, page) {
                var more = (page * 10) < data.total; // whether or not there are more results available

                // notice we return the value of more so Select2 knows if more results can be loaded
                return {results: data.ies, more: more};
            }
        },
        formatResult: function (institution) {
            var markup = "<table class='institution-result'><tr>";
            markup += "<td class='institution-info'><div class='institution-name'>" + institution.name + "</div>";
            markup += "</td></tr></table>";
            return markup;
        },
        formatSelection: function (institution) {
            return institution.name;
        },
        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
        escapeMarkup: function (m) {
            return m;
        } // we do not want to escape markup since we are displaying html in results
    });
    $(formInstructorvariableData + "high_education_institution_code_1_fk, "
        + formInstructorvariableData + "high_education_institution_code_2_fk, "
        + formInstructorvariableData + "high_education_institution_code_3_fk").select2('enable', true);
    $(formInstructorIdentification + "edcenso_nation_fk").select2("disable", "true");
    $(formInstructorIdentification + "edcenso_uf_fk").select2("disable", "true");
    $(formInstructorIdentification + "edcenso_city_fk").select2("disable", "true");
    $(formInstructorIdentification + "nationality").trigger("change");
});

$("#InstructorIdentification_deficiency").change(function () {
    if ($(this).is(":checked")) {
        $("#InstructorIdentification_deficiencies").parent(".control-group").show();
    } else {
        $("#InstructorIdentification_deficiencies").parent(".control-group").hide();
    }
});
$("#InstructorIdentification_deficiency").trigger("change");

$("#InstructorIdentification_filiation").change(function () {
    if ($(this).val() == "1") {
        $("#InstructorIdentification_filiation_1").removeAttr("disabled");
        $("#InstructorIdentification_filiation_2").removeAttr("disabled");
    } else {
        $("#InstructorIdentification_filiation_1").val("").attr("disabled", "disabled");
        $("#InstructorIdentification_filiation_2").val("").attr("disabled", "disabled");
    }
});

if($("#show-instructor-civil-name").is(":checked")) {
    $(".instructor-civil-name").show();
    $(".show-instructor-civil-name-box").hide();
}

$(document).on("change", "#show-instructor-civil-name", function () {
    if($(this).is(":checked")) {
        $(".instructor-civil-name").show();
        $(".show-instructor-civil-name-box").hide();
    }
});

$("#InstructorIdentification_filiation").trigger("change");