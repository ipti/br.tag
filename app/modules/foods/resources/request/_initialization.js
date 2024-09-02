let foodsRelation = [];
let foodRequests;

$(document).ready(function() {
    let foodSelect = $('#foodSelect');
    let searchByFoodSelect = $('#searchByFoodSelect');
    let foodNotice = $('#foodNotice');


    $.ajax({
        type: 'POST',
        url: "?r=foods/foodrequest/getFoodRequest",
        cache: false
    }).success(function(response) {
        let data = DOMPurify.sanitize(response);
        foodRequests = JSON.parse(data);
        console.log(foodRequests);
        renderRequestTable(foodRequests);
    })

    $.ajax({
        type: 'POST',
        url: "?r=foods/foodrequest/getFoodAlias",
        cache: false
    }).success(function(response) {
        let data = DOMPurify.sanitize(response);
        let foods_description = JSON.parse(data);

        Object.entries(foods_description).forEach(function([id, value]) {
            let description = value.description.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            value = id + ',' + value.measurementUnit + ','+ value.category;
            foodSelect.append($('<option>', {
                value: value,
                text: description
            }));
            searchByFoodSelect.append($('<option>', {
                value: value,
                text: description
            }));
        });
    })

    $.ajax({
        type: 'POST',
        url: "?r=foods/farmerregister/getFoodNotice",
        cache: false
    }).success(function(response) {
        let data = DOMPurify.sanitize(response);
        let food_notices = JSON.parse(data);

        Object.entries(food_notices).forEach(function([id, value]) {
            foodNotice.append($('<option>', {
                value: id,
                text: value.name
            }));
        });
    });

    $('#searchByFoodSelect').on('change', function() {
        let foodId = $(this).val().split(',')[0];

        if(foodId == "total") {
            renderRequestTable(foodRequests);
        } else {
            renderRequestTable(foodRequests, foodId);
        }
    });

    $(".js-select-farmer, .js-select-schools").select2({
        maximumSelectionSize: 6,
    });
})

$(document).on("click", "#js-information-button", function () {
    $("#js-request-information-modal").modal("show");
    let foodRequestId = $(this).attr('data-requestId');

    let requestData = foodRequests.find(request => request.requestInfo.id == foodRequestId);
    let schools = requestData.schools.map(school =>
        school.name
    ).join(', ');
    let farmers = requestData.farmers.map(farmer =>
        farmer.name
    ).join(', ');

    let $requestData = `
        <div class="row">
            <div class="column clearfix">
                <p><b>Edital:</b> ${requestData.requestInfo.notice}</p>
                <p><b>Escolas:</b> ${schools}</p>
                <p><b>Agricultores:</b> ${farmers}</p>
            </div>
        </div>
    `;
    let $requestItems = `
        <div class="row">
        <div class="column clearfix">
        <table id="requestItemsTable"  aria-describedby="requestItemsTable" class="tag-table-secondary align-start no-margin">
            <tr>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Unidade</th>
            </tr>
    `;
    $.each(requestData.items, function(index, item) {
        $requestItems += `
            <tr>
                <td>${item.foodName.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '').trim()}</td>
                <td>${item.amount}</td>
                <td>${item.measurementUnit}</td>
            </tr>
        `;
    });
    let $requestItemsReceived = `
        <div class="row">
        <div class="column clearfix">
        <table id="requestItemsReceivedTable"  aria-describedby="requestItemsReceivedTable" class="tag-table-secondary align-start no-margin">
            <tr>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Agricultor</th>
                <th>Data</th>
            </tr>
    `;
    $.each(requestData.itemsReceived, function(index, item) {
        $requestItemsReceived += `
            <tr>
                <td>${item.foodName.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '').trim()}</td>
                <td>${item.amount} (${item.measurementUnit})</td>
                <td>${item.farmerName}</td>
                <td>${item.date}</td>
            </tr>
        `;
    });
    $requestItemsReceived += `
        </table>
        </div>
        </div>
    `;
    $("#requestData").html($requestData);
    $("#requestItems").html($requestItems);
    $("#requestItemsReceived").html($requestItemsReceived);
});

$(document).on("click", "#save-request", function () {
    let noticeId = $('#foodNotice').val();
    let requestSchools = $('#requestSchools').find('option:selected').map(function() {
        return {
            id: $(this).val(),
            name: $(this).text()
        };
    }).get();
    let requestFarmers = $('#requestFarmers').val();

    if(!requestSchools || !requestFarmers || foodsRelation.length == 0) {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    } else {
        let requestTitle = foodsRelation.map(item =>
            item.foodName.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '').trim()
        ).join(', ');
        $.ajax({
            type: 'POST',
            url: "?r=foods/foodrequest/create",
            cache: false,
            data: {
                noticeId: noticeId,
                requestSchools: requestSchools,
                requestFarmers: requestFarmers,
                requestItems: foodsRelation,
                requestTitle: requestTitle
            }
        }).success(function(response) {
            window.location.href = "?r=foods/foodRequest/index";
        })
    }
});

$(document).on("click", "#js-add-food", function () {
    let food = $('#foodSelect').find('option:selected').text().trimEnd();
    let foodId = $('#foodSelect').val().split(',')[0];
    let foodCategory = $('#foodSelect').val().split(',')[2];
    let amount = $('#amount').val();
    let measurementUnit = $('#measurementUnit').find('option:selected').text();

    if(foodId == "alimento" || amount == "") {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    } else if(amount !== "" && !isNaN(amount) && parseFloat(amount) >= 0 && amount.indexOf(',') === -1) {
        let existingIndex = $.map(foodsRelation, function(obj, index) {
            return obj.food_id === foodId ? index : null;
        })[0];

        if(existingIndex !== undefined) {
            foodsRelation[existingIndex].amount = amountCalculation(foodsRelation[existingIndex].amount, amount, measurementUnit, foodsRelation[existingIndex].measurementUnit);
        } else {
            foodsRelation.push({food_id: foodId, foodName: food, amount: amount, measurementUnit: measurementUnit, category: foodCategory});
        }
        renderFoodsTable(foodsRelation);
    } else {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Quantidade informada não é válida, utilize números positivos e se decimal, separe por '.'");
    }
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

$(document).on("click", "#remove-food-button", function () {
    let id = $(this).attr('data-foodId');
    foodsRelation.splice(id, 1);
    renderFoodsTable(foodsRelation);
});
