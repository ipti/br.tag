let foodsOnStock = [];
let table = $('#foodStockTable');

$(document).ready(function() {
    let food_inventory;
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodInventory/getFoodInventory",
        cache: false
    }).success(function(response) {
        food_inventory = JSON.parse(response);
        food_inventory.sort((a, b) => b.amount - a.amount);
        renderStockTable(food_inventory);
    })
    let foodSelect = $('#foodStockSelect');
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodInventory/getFoodAlias",
        cache: false
    }).success(function(response) {
        foods_description = JSON.parse(response);

        Object.entries(foods_description).forEach(function([id, value]) {
            value = value.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            foodSelect.append($('<option>', {
                value: id,
                text: value
            }));
        });
    })

    $('#foodStockSelect').on('change', function() {
        var foodId = $(this).val();

        if(foodId == "total") {
            renderStockTable(food_inventory);
        } else {
            renderStockTable(food_inventory, foodId);
        }
    });
});

$(document).on("click", "#js-movements-button", function () {
    $("#js-movements-modal").modal("show");
    let foodInventoryFoodId = $(this).attr('data-foodInventoryFoodId');
    let foodName = $(this).attr('data-foodInventoryFoodName');

    $.ajax({
        type: 'POST',
        url: "?r=foods/foodInventory/getStockMovement",
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
    let foodSelect = $('#food');

    $.ajax({
        type: 'POST',
        url: "?r=foods/foodInventory/getFoodAlias",
        cache: false
    }).success(function(response) {
        foods_description = JSON.parse(response);

        Object.entries(foods_description).forEach(function([id, value]) {
            value = value.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            foodSelect.append($('<option>', {
                value: id,
                text: value
            }));
        });
    })
});

$(document).on("click", "#add-food", function () {
    let food = $('#food').find('option:selected').text();
    let measurementUnit = $('#measurementUnit').find('option:selected').text();
    let foodId = $('#food').val();
    let amount = $('.js-amount').val();
    let expiration_date = $('.js-expiration-date').val();

    foodsOnStock.push({id: foodId, foodDescription: food, amount: amount, measurementUnit: measurementUnit ,expiration_date: expiration_date});
    renderSelectedFoods(foodsOnStock);
});

$(document).on("click", "#stock_button", function () {
    let id = $(this).attr('data-buttonId');
    foodsOnStock.splice(id, 1);
    renderSelectedFoods(foodsOnStock);
});

$(document).on("click", "#save-food", function () {
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodInventory/saveStock",
        cache: false,
        data: {
            foodsOnStock: foodsOnStock
        }
    }).success(function(response) {
        foodsOnStock.splice(0, foodsOnStock.length);
        let foodsStockDiv = document.getElementById("foods_stock");
        foodsStockDiv.innerHTML = '';
        $('.js-expiration-date').val('');
        $('.js-amount').val('');
        $('#info-alert').removeClass('hide').addClass('alert-success').html("Alimento(s) adicionado(s) ao estoque com sucesso.");
        getFoodInventory();
    }).fail(function(error) {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível adicionar o alimento no sistema.");
    })
});

$(document).on("click", "#spent-checkbox", function () {
    let foodInventoryId = $(this).attr('data-foodInventoryId');
    let amount = $(this).attr('data-amount');
    console.log($(this).is(':checked'));
    if($(this).is(':checked')) {
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodInventory/saveStockSpent",
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

table.on('change', '#foodInventoryStatus', function() {
    let status = $(this).val();
    let foodInventoryId =  $(this).attr('data-foodInventoryId');
    let amount = $(this).attr('data-amount');
    console.log(status);

    if(status == 'Emfalta') {
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodInventory/saveStockSpent",
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
            url: "?r=foods/foodInventory/updateFoodInventoryStatus",
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
