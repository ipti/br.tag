let foodsOnStock = [];
let table = $('#foodStockTable');
let food_inventory = [];

$(document).ready(function() {
    getFoodInventory();
    let foodSelect = $('#foodStockSelect');
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodinventory/getFoodAlias",
        cache: false
    }).success(function(response) {
        let foods_description = JSON.parse(response);

        Object.entries(foods_description).forEach(function([id, value]) {
            value = value.description.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            foodSelect.append($('<option>', {
                value: id,
                text: value
            }));
        });
    })
});

function getFoodInventory() {
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodinventory/getFoodInventory",
        cache: false
    }).success(function(response) {
        food_inventory = JSON.parse(response);
        food_inventory.sort((a, b) => b.amount - a.amount);
        renderStockTable(food_inventory);
        renderStockList(food_inventory);
    })
};

$(document).on("change", "#foodStockSelect, #foodStatusFilter", function () {
    var foodId = $('#foodStockSelect').val();
    var statusValue = $('#foodStatusFilter').val();

    let id = foodId === "total" ? undefined : foodId;
    let status = statusValue === "total" ? undefined : statusValue;

    renderStockTable(food_inventory, id, status);
    renderStockList(food_inventory, id, status);
});

$(document).on("click", "#js-movements-button", function () {
    $("#js-movements-modal").modal("show");
    let foodInventoryFoodId = $(this).attr('data-foodInventoryFoodId');
    let foodName = $(this).attr('data-foodInventoryFoodName');

    $.ajax({
        type: 'POST',
        url: "?r=foods/foodinventory/getStockMovement",
        cache: false,
        data: {
            foodInventoryFoodId: foodInventoryFoodId
        }
    }).success(function(response) {
        movements = JSON.parse(response);
        movements.sort((a, b) => {
            const dateA = new Date(a.date.split('/').reverse().join('/'));
            const dateB = new Date(b.date.split('/').reverse().join('/'));
            return dateB - dateA;
        });
        renderMovementsTable(movements, foodName);
    })
})

$(document).on("click", "#js-entry-stock-button", function () {
    $("#js-entry-stock-modal").modal("show");
    $('.js-date').mask("99/99/9999");
    $(".js-date").datepicker({
        language: "pt-BR",
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
    let foodSelect = $('#food');

    $.ajax({
        type: 'POST',
        url: "?r=foods/foodinventory/getFoodAlias",
        cache: false
    }).success(function(response) {
        foods_description = JSON.parse(response);

        Object.entries(foods_description).forEach(function([id, value]) {
            description = value.description.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            value = id + ',' + value.measurementUnit;
            foodSelect.append($('<option>', {
                value: value,
                text: description
            }));
        });
    })
});

$(document).on("click", "#js-status-button", function () {
    $("#js-status-modal").modal("show");
    let status = $(this).attr('data-foodStatus');
    let foodInventoryId = $(this).attr('data-foodInventoryId');
    let amount = $(this).attr('data-amount');

    $("#js-saveFoodInventoryStatus").attr("data-foodInventoryId", foodInventoryId);
    $("#js-saveFoodInventoryStatus").attr("data-amount", amount);

    $("#js-status-select").val(status);
    $("#js-status-select").trigger("change");
});

$(document).on("click", "#add-food", function () {
    let food = $('#food').find('option:selected').text();
    let measurementUnit = $('#measurementUnit').find('option:selected').text();
    let foodId = $('#food').val().split(',')[0];
    let amount = $('.js-amount').val();
    let expiration_date = $('.js-expiration-date').val();

    if(foodId == "alimento" || amount == "") {
        $('#stock-modal-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    } else {
        if(amount !== "" && !isNaN(amount) && parseFloat(amount) >= 0 && amount.indexOf(',') === -1) {
            foodsOnStock.push({id: foodId, foodDescription: food, amount: amount, measurementUnit: measurementUnit ,expiration_date: expiration_date});
            renderSelectedFoods(foodsOnStock);
        } else {
            $('#stock-modal-alert').removeClass('hide').addClass('alert-error').html("Quantidade informada não é válida, utilize números positivos e se decimal, separe por '.'");
        }
    }
});

$(document).on("click", "#stock_button", function () {
    let id = $(this).attr('data-buttonId');
    foodsOnStock.splice(id, 1);
    renderSelectedFoods(foodsOnStock);
});

$(document).on("click", "#save-food", function () {
    console.log(foodsOnStock);
    if (foodsOnStock != 0){
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodinventory/saveStock",
            cache: false,
            data: {
                foodsOnStock: foodsOnStock
            }
        }).success(function(response) {
            foodsOnStock.splice(0, foodsOnStock.length);
            let foodsStockDiv = document.getElementById("foods_stock");
            foodsStockDiv.innerHTML = '';
            $('#js-entry-stock-modal').modal('hide');
            $('.js-expiration-date').val('');
            $('.js-amount').val('');
            $('#info-alert').removeClass('hide').addClass('alert-success').html("Alimento(s) adicionado(s) ao estoque com sucesso.");
            getFoodInventory();
        }).fail(function(error) {
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível adicionar o alimento no sistema.");
        })
    } else {
        $('#stock-modal-alert').removeClass('hide').addClass('alert-error').html("Para adicionar ao estoque é necessário adicionar um alimento");
    }
});

$(document).on("click", "#spent-checkbox", function () {
    let foodInventoryId = $(this).attr('data-foodInventoryId');
    let amount = $(this).attr('data-amount');
    console.log($(this).is(':checked'));
    if($(this).is(':checked')) {
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodinventory/saveStockSpent",
            cache: false,
            data: {
                foodInventoryId: foodInventoryId,
                amount: amount
            }
        }).success(function(response) {
            getFoodInventory();
            $('#spent-checkbox').prop('disabled', true);
            $('#info-alert').removeClass('hide').addClass('alert-success').html("Estoque de alimento retirado do sistema com sucesso.");
        }).fail(function(error) {
            $('#spent-checkbox').prop('checked', false);
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível retirar o estoque do alimento do sistema.");
        })


    }
});

$(document).on("click", "#js-saveFoodInventoryStatus", function () {
    let status = $("#js-status-select").val();
    let foodInventoryId =  $(this).attr('data-foodInventoryId');
    let amount = $(this).attr('data-amount');

    if(status == 'Emfalta') {
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodinventory/saveStockSpent",
            cache: false,
            data: {
                foodInventoryId: foodInventoryId,
                amount: amount
            }
        }).success(function(response) {
            getFoodInventory();
            $('#info-alert').removeClass('hide').addClass('alert-success').html("Estoque de alimento retirado do sistema com sucesso.");
        }).fail(function(error) {
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível retirar o estoque do alimento do sistema.");

        })
    } else {
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodinventory/updateFoodInventoryStatus",
            cache: false,
            data: {
                foodInventoryId: foodInventoryId,
                status: status
            }
        }).success(function(response) {
            getFoodInventory();
            $('#info-alert').removeClass('hide').addClass('alert-success').html("Status do alimento modificado com sucesso.");
        }).fail(function(error) {
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível modificar o status do alimento.");
        })
    }
});

table.on('change', '#foodInventoryStatus', function() {
    let status = $(this).val();
    let foodInventoryId =  $(this).attr('data-foodInventoryId');
    let amount = $(this).attr('data-amount');
    console.log(status);

    if(status == 'Emfalta') {
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodinventory/saveStockSpent",
            cache: false,
            data: {
                foodInventoryId: foodInventoryId,
                amount: amount
            }
        }).success(function(response) {
            getFoodInventory();
            $('#info-alert').removeClass('hide').addClass('alert-success').html("Estoque de alimento retirado do sistema com sucesso.");
        }).fail(function(error) {
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível retirar o estoque do alimento do sistema.");

        })
    } else {
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodinventory/updateFoodInventoryStatus",
            cache: false,
            data: {
                foodInventoryId: foodInventoryId,
                status: status
            }
        }).success(function(response) {
            $('#info-alert').removeClass('hide').addClass('alert-success').html("Status do alimento modificado com sucesso.");
        }).fail(function(error) {
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível modificar o status do alimento.");
        })
    }

});

$(document).on("change", "#food", function () {
    let measurementUnit = this.value.split(',')[1];
    let measurementUnitSelect = $('#measurementUnit');
    measurementUnitSelect.empty();
    switch (measurementUnit) {
        case "g":
            measurementUnitSelect.append($('<option value="g" selected>g</option><option value="Kg">Kg</option>'));
            break;
        case "u":
            measurementUnitSelect.append($('<option value="unidade" selected>Unidade</option><option value="g">g</option><option value="Kg">Kg</option>'));
            break;
        case "l":
            measurementUnitSelect.append($('<option value="l" selected>L</option>'));
            break;
    }
    measurementUnitSelect.val('');
    measurementUnitSelect.trigger("change");
});
