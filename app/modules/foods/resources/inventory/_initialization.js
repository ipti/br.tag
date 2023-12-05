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
    let foodsStockDiv = document.getElementById("foods_stock");
    let div_stock_quantity = foodsStockDiv.querySelectorAll('.mobile-row').length;

    let stock = `
    <div class="mobile-row t-list-content" id="food_stock_${div_stock_quantity + 1}">
        <div class="column is-two-fifths clearfix">${food}</div>
        <div class="column is-one-fifth clearleft--on-mobile clearfix">${amount}</div>
        <div class="column is-one-fifth clearleft--on-mobile clearfix">${expiration_date}</div>
        <div class="column is-one-fifth clearleft--on-mobile clearfix justify-content--end">
            <span class="t-icon-close t-icon" id="stock_button" data-buttonId="${div_stock_quantity + 1}"></span>
        </div>
    </div>
    `;

    foodsStockDiv.innerHTML += stock;
});

$(document).on("click", "#stock_button", function () {
    let id = $(this).attr('data-buttonId');
    $("#food_stock_" + id).remove();
});
