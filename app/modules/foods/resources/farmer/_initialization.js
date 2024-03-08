let foodsRelation = [];

$(document).ready(function() {
    let foodSelect = $('#foodSelect');

    const $params = new URLSearchParams(window.location.search);
    const $id = $params.get('id');

    if($id != null) {
        $.ajax({
            type: 'POST',
            url: "?r=foods/farmerregister/getFarmerFoods",
            cache: false,
            data: {
                id: $id,
            }
        }).success(function(response) {
            let farmerFoods = JSON.parse(response);

            console.log(farmerFoods);
        });
    }

    $.ajax({
        type: 'POST',
        url: "?r=foods/farmerregister/getFoodAlias",
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

    if(foodId == "alimento" || amount == "") {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    } else {
        if(amount !== "" && !isNaN(amount) && parseFloat(amount) >= 0 && amount.indexOf(',') === -1) {
            let existingIndex = $.map(foodsRelation, function(obj, index) {
                return obj.id === foodId ? index : null;
            })[0];

            if(existingIndex !== undefined) {
                foodsRelation[existingIndex].amount = parseFloat(foodsRelation[existingIndex].amount) + parseFloat(amount);
            } else {
                foodsRelation.push({id: foodId, foodDescription: food, amount: amount, measurementUnit: measurementUnit});
            }
            renderFoodsTable(foodsRelation);
        } else {
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Quantidade informada não é válida, utilize números positivos e se decimal, separe por '.'");
        }
    }
});

$(document).on("click", "#remove-food-button", function () {
    let id = $(this).attr('data-foodId');
    foodsRelation.splice(id, 1);
    renderFoodsTable(foodsRelation);
});

$(document).on("click", "#save-farmer", function () {
    let name = $("#farmerName").val();
    let cpf = $("#farmerCpf").val().replace(/[^0-9]/g, '');
    let phone = $("#farmerPhone").val().replace(/[^0-9]/g, '');
    let groupType = $('#farmerGroupType').find('option:selected').text();

    $.ajax({
        type: 'POST',
        url: "?r=foods/farmerRegister/saveFarmerRegister",
        cache: false,
        data: {
            name: name,
            cpf: cpf,
            phone: phone,
            groupType: groupType,
            foodsRelation: foodsRelation
        }
    })
})

$('#farmerCpf').mask("000.000.000-00", {
    placeholder: "___.___.___-__",
});
$('#farmerPhone').mask("(00) 00000-0000", {
    placeholder: "(__) _____-____",
});
