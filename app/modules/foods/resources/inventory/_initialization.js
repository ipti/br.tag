$(document).on("click", "#js-entry-stock-button", function () {
    $("#js-entry-stock-modal").modal("show");
    $('.js-date').mask("99/99/9999");
    $(".js-date").datepicker({
    locate: "pt-BR",
    format: "dd/mm/yyyy",
    autoclose: true,
    todayHighlight: true,
    allowInputToggle: true,
    disableTouchKeyboard: true,
    keyboardNavigation: false,
    orientation: "bottom left",
    clearBtn: true,
    maxViewMode: 2,
    showClearButton: false
    });
});

$(document).on("click", "#add-food", function () {
    let food = $('#food').val();
    let amount = $('.js-amount').val();
    let expiration_date = $('.js-expiration-date').val();

    console.log("Comida:" + food + "Quantidade:" + amount + "Validade:" + expiration_date);

    let stock = `
    <div class="row">
        <div class="column is-two-fifths clearfix"></div>
        <div class="column is-one-fifth clearleft--on-mobile clearfix"></div>
        <div class="column is-one-fifth clearleft--on-mobile clearfix"></div>
    </div>`;
});
