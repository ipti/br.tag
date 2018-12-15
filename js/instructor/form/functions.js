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