let foodRequests = [];

$(document).ready(function() {
    let food_request;
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodrequest/getFoodRequest",
        cache: false
    }).success(function(response) {
        food_request = JSON.parse(response);
        food_request.sort((a, b) => (a.delivered === false && b.delivered === true) ? -1 : 1);
        renderRequestTable(food_request);
    })

    let foodSelect = $('#foodRequestSelect');
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodinventory/getFoodAlias",
        cache: false
    }).success(function(response) {
        foods_description = JSON.parse(response);

        Object.entries(foods_description).forEach(function([id, value]) {
            value = value.description.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            foodSelect.append($('<option>', {
                value: id,
                text: value
            }));
        });
    })

    $('#foodRequestSelect').on('change', function() {
        var foodId = $(this).val();

        if(foodId == "total") {
            renderRequestTable(food_request);
        } else {
            renderRequestTable(food_request, foodId);
        }
    });
})

$(document).on("click", "#js-entry-request-button", function () {
    $("#js-entry-request-modal").modal("show");
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
})

$(document).on("click", "#add-request", function () {
    let amount = $('.js-amount').val();
    let food = $('#food').find('option:selected').text();
    let measurementUnit = $('#measurementUnit').find('option:selected').text();
    let foodId = $('#food').val().split(',')[0];
    let description = $('.js-description').val();
    let requestDate = $('.js-date').val();

    if(foodId == "alimento" || amount == "") {
        $('#request-modal-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    } else {
        if (amount !== "" && !isNaN(amount) && parseFloat(amount) >= 0 && amount.indexOf(',') === -1) {
            foodRequests.push({id: foodId, foodDescription: food, amount: amount, measurementUnit: measurementUnit , date: requestDate, description: description});
            renderSelectedRequests(foodRequests);
        } else {
            $('#request-modal-alert').removeClass('hide').addClass('alert-error').html("Quantidade informada não é válida, utilize números positivos e se decimal, separe por '.'");
        }
    }
})

$(document).on("click", "#request_button", function () {
    let id = $(this).attr('data-buttonId');
    foodRequests.splice(id, 1);
    renderSelectedRequests(foodRequests);
});

$(document).on("click", "#save-request", function () {
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodrequest/saveRequest",
        cache: false,
        data: {
            foodRequests: foodRequests
        }
    }).success(function(response) {
        foodRequests.splice(0, foodRequests.length);
        let foodRequestsDiv = document.getElementById("food_request");
        foodRequestsDiv.innerHTML = '';
        $('.js-date').val('');
        $('.js-amount').val('');
        $('.js-description').val('');
        $('#info-alert').removeClass('hide').addClass('alert-success').html("Solicitação gerada com sucesso.");
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodrequest/getFoodRequest",
            cache: false
        }).success(function(response) {
            food_request = JSON.parse(response);
            food_request.sort((a, b) => (a.delivered === false && b.delivered === true) ? -1 : 1);
            renderRequestTable(food_request);
        });
    }).fail(function(error) {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível gerar a solicitação no sistema.");
    })
});
$(document).on("change", "#food", function () {
    let measurementUnit = this.value.split(',')[1];
    let measurementUnitSelect = $('#measurementUnit');
    measurementUnitSelect.empty();
    console.log(measurementUnit);
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
