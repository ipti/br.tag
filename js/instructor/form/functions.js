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
    setTimeout(function() {
        $(formDocumentsAndAddress + 'edcenso_city_fk')
                .val(data['City'])
                .trigger('change')
                .select2('readonly', data.City != null);
    }, 500);

}

$("#InstructorIdentification_edcenso_uf_fk").on("change", function () {
    $('#InstructorIdentification_edcenso_city_fk option:not(:contains("Selecione uma cidade"))').remove();
    $.ajax({
        type: "POST",
        url: "?r=instructor/getCity",
        data: {
            edcenso_uf_fk: $(this).val(),
        },
        success: function (response) {
            $.each(JSON.parse(response), function (id, option) {
                $("#InstructorIdentification_edcenso_city_fk").append(option);
            })
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
        },
        success: function (response) {
            $.each(JSON.parse(response), function (id, option) {
                $("#InstructorDocumentsAndAddress_edcenso_city_fk").append(option);
            })
        }
    });
});