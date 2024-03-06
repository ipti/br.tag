let foodsRelation = [];

$(document).ready(function() {
    let foodSelect = $('#foodSelect');

    $.ajax({
        type: 'POST',
        url: "?r=foods/foodInventory/getFoodAlias",
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

$(document).on("change", "#foodSelect", function () {
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

$(document).on("click", "#js-add-food", function () {
    let food = $('#foodSelect').find('option:selected').text();
    let foodId = $('#foodSelect').val().split(',')[0];
    let amount = $('#amount').val();
    let measurementUnit = $('#measurementUnit').find('option:selected').text();

    foodsRelation.push({id: foodId, foodDescription: food, amount: amount, measurementUnit: measurementUnit});
    renderFoodsTable(foodsRelation);
});

$(document).on("click", "#remove-food-button", function () {
    let id = $(this).attr('data-foodId');
    foodsRelation.splice(id, 1);
    renderFoodsTable(foodsRelation);
});
