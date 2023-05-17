$(document).ready(function () {
    $('#input_responsible_cpf').mask('000.000.000-00', { placeholder: "___.___.___-__" });
    $('#input_manager_cpf').mask('000.000.000-00', { placeholder: "___.___.___-__" });

    $('#input_responsible_cpf, #input_manager_cpf').focusout(function () {
        var id = '#' + $(this).attr("id");
        removeError(id);
        const validationState = validateCpf($(this).cleanVal());
        if (!validationState.valid) {
            addError(id, "Informe um CPF válido.");
        } else {
            removeError(id);
        }
    });
});
