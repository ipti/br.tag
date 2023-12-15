let foodRequests = [];

$(document).ready(function() {
    let food_request;
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodRequest/getFoodRequest",
        cache: false
    }).success(function(response) {
        food_request = JSON.parse(response);
        food_request.sort((a, b) => (a.delivered === false && b.delivered === true) ? -1 : 1);
        renderRequestTable(food_request);
    })

    let foodSelect = $('#foodRequestSelect');
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
})

$(document).on("click", "#add-request", function () {
    let food = $('#food').find('option:selected').text();
    let measurementUnit = $('#measurementUnit').find('option:selected').text();
    let foodId = $('#food').val();
    let amount = $('.js-amount').val();
    let description = $('.js-description').val();
    let requestDate = $('.js-date').val();

    foodRequests.push({id: foodId, foodDescription: food, amount: amount, measurementUnit: measurementUnit , date: requestDate, description: description});
    renderSelectedRequests(foodRequests);
})

$(document).on("click", "#request_button", function () {
    let id = $(this).attr('data-buttonId');
    foodRequests.splice(id, 1);
    renderSelectedRequests(foodRequests);
});

$(document).on("click", "#save-request", function () {
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodRequest/saveRequest",
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
            url: "?r=foods/foodRequest/getFoodRequest",
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

$(document).on("click", "#delivery-checkbox", function () {
    let foodRequestId = $(this).attr('data-foodRequestId');
    if($(this).is(':checked')) {
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodRequest/saveRequestDelivered",
            cache: false,
            data: {
                foodRequestId: foodRequestId
            }
        }).success(function(response) {
            $('#info-alert').removeClass('hide').addClass('alert-success').html("Entrega confirmada e estoque atualizado");
            $.ajax({
                type: 'POST',
                url: "?r=foods/foodRequest/getFoodRequest",
                cache: false
            }).success(function(response) {
                food_request = JSON.parse(response);
                food_request.sort((a, b) => (a.delivered === false && b.delivered === true) ? -1 : 1);
                renderRequestTable(food_request);
            });
        })
        .fail(function(error) {
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível confirmar a entrega e atualizar o estoque");
        })
    }
});