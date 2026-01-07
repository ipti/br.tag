$("form").on("submit", function (event) {
    let isValid = true;
    let errors = [];
    let mensage = "";

    $(".js-field-required").each(function () {
        if ($(this).is("input, select, textarea") && !$(this).val() && $(this).hasClass("js-ignore-validation") === false) {
            isValid = false;

            let labelText = $(this).closest(".t-field-text").find("label").text().trim();

            errors.push(labelText.replace(/\*/g, ""));
        }
    });

    if (!isValid) {
        event.preventDefault();
        errors.forEach(function (fieldName) {
            mensage += "O campo <b>" + fieldName + "</b> é obrigatório.<br>";
        });
        $(".js-alert").html(mensage).show();
    } else {
        $(".js-alert").html('').hide();
    }
})
