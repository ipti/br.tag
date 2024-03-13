$(".js-notice-year-amount").on('input', (e) =>{
    const inputValue = e.target.value;

    // Verifica se o valor contém letras ou caracteres diferentes de '.'
    if (/[^0-9.]/.test(inputValue)) {
        // Remove os caracteres que não são números ou pontos
        const sanitizedValue = inputValue.replace(/[^0-9.,]/g, '');

        // Atualiza o valor do campo apenas com os caracteres permitidos
        $(e.target).val(sanitizedValue);
    }
})