let foodsOnStock = [];

$(document).ready(function() {
    let food_inventory;
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodInventory/getFoodInventory",
        cache: false
    }).success(function(response) {
        food_inventory = JSON.parse(response);
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
    let foodInventoryId = $(this).attr('data-foodInventoryId');
    console.log(foodInventoryId);

    $.ajax({
        type: 'POST',
        url: "?r=foods/foodInventory/getStockMovement",
        cache: false,
        data: {
            foodInventoryId: foodInventoryId
        }
    }).success(function(response) {
        movements = JSON.parse(response);
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
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodInventory/getFoodInventory",
            cache: false
        }).success(function(response) {
            food_inventory = JSON.parse(response);
            renderStockTable(food_inventory);
        })
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
        })
    } else {
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodInventory/deleteStockSpent",
            cache: false,
            data: {
                foodInventoryId: foodInventoryId,
            }
        })
    }
});
