/**
 * Pega as informações do CEP e atualiza os campos necessários.
 *
 * @param {structure} array(UF, City)
 * @returns {void}
 * */

function updateCep(data) {
    data = jQuery.parseJSON(data);
    if (data.UF == null)
        $(formDocumentsAndAddress + 'cep').val('').trigger('focusout');
    $(formDocumentsAndAddress + 'edcenso_uf_fk')
        .val(data['UF'])
        .trigger('change')
        .select2('readonly', data.UF != null);
    setTimeout(function () {
        $(formDocumentsAndAddress + 'edcenso_city_fk')
            .val(data['City'])
            .trigger('change')
            .select2('readonly', data.City != null);
    }, 500);

}

$("#InstructorIdentification_edcenso_uf_fk").on("change", function () {
    $.ajax({
        type: "POST",
        url: "?r=instructor/getCity",
        data: {
            edcenso_uf_fk: $(this).val(),
            current_city: $("#InstructorIdentification_edcenso_city_fk").val()
        },
        success: function (response) {
            // const optionsList = JSON.parse(response);
            // const options = optionsList.join("");
            $("#InstructorIdentification_edcenso_city_fk").html(options);
            $("#InstructorIdentification_edcenso_city_fk").select2();
        }
    });
});

$("#InstructorDocumentsAndAddress_edcenso_uf_fk").on("change", function () {
    $('#InstructorDocumentsAndAddress_edcenso_city_fk option:not(:contains("Selecione uma cidade"))').remove();
    $.ajax({
        type: "POST",
        url: "?r=instructor/getCity",
        data: {
            edcenso_uf_fk: $(this).val(),
            current_city: $("#InstructorDocumentsAndAddress_edcenso_city_fk").val()
        },
        success: function (response) {
            $.each(JSON.parse(response), function (id, option) {
                $("#InstructorDocumentsAndAddress_edcenso_city_fk").append(option);
            })
        }
    });
});
$("#IES").on("change", function () {
    loadIES("#IES", "#InstructorVariableData_high_education_institution_code_1_fk");
});

$(function () {
    const currentIES = $("#InstructorVariableData_high_education_institution_code_1_fk").val();
    loadIES("#IES", "#InstructorVariableData_high_education_institution_code_1_fk", currentIES);
});


function loadIES(iesUfDropDown, iesDropDownPath, currentIES) {
    console.log(currentIES)
    $.ajax({
        type: "POST",
        url: "?r=instructor/getinstitution",
        data: {
            edcenso_uf_fk: $(iesUfDropDown).val(),
        },
        success: function (response) {
            var options = response.map((item) => {
                return $(`<option value=${item.id} >${item.name}</option>`)
            },

            );
            options.push("<option value=9999999>OUTRO</option>")
            $(iesDropDownPath).html(options);
            $(iesDropDownPath).select2("val", currentIES);
            //  $("#s2id_InstructorVariableData_high_education_institution_code_1_fk").prop("disabled", false);
        }
    });
}
