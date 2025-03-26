function renderRequestTable(foodRequests, id) {
    let table = $('#foodRequestTable');
    table.empty();

    let head = $('<tr>').addClass('');
    $('<th>').text('Id').appendTo(head);
    $('<th>').text('Itens').appendTo(head);
    $('<th>').text('Data de solicitação').appendTo(head);
    $('<th>').text('Status').appendTo(head);
    $('<th>').text('Informações').appendTo(head);

    table.append(head);

    if (typeof id === 'undefined') {
        $.each(foodRequests, function(index, request) {
            let row = $('<tr>').addClass('');
            let requestItems = request.items.map(item =>{
                if (item.foodName) {
                    return item.foodName.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '').trim();
                } else {
                    return '';
                }
            }).join(', ');
            $('<td>').text(request.requestInfo.id).appendTo(row);
            $('<td>').text(requestItems).appendTo(row);
            $('<td>').text(request.requestInfo.date).appendTo(row);
            $('<td style="padding-right: 25px">')
            .html('<button style="cursor: default" class="' + (request.requestInfo.status === "Finalizado" ? "t-button-success" : "t-button-secondary") + ' full--width t-margin-none--right" id="js-status-button" type="button">'+ request.requestInfo.status +'</button>')
            .appendTo(row);
            $('<td>').html('<div class="full justify-content--center"><span class="t-icon-search_icon t-badge-info__icon cursor-pointer" id="js-information-button" data-requestId="' + request.requestInfo.id + '"></span></div>').appendTo(row);
            table.append(row);
        })
    } else {
        let found = false;
        $.each(foodRequests, function(index, request) {
            let hasTargetFoodId = request.items.some(function(item) {
                return item.foodId === id;
            });

            if (hasTargetFoodId) {
                found = true;
                let row = $('<tr>').addClass('');
                let requestItems = request.items.map(item =>
                    item.foodName.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '').trim()
                ).join(', ');
                $('<td>').text(request.requestInfo.id).appendTo(row);
                $('<td>').text(requestItems).appendTo(row);
                $('<td>').text(request.requestInfo.date).appendTo(row);
                $('<td style="padding-right: 25px">')
                .html('<button style="cursor: default" class="' + (request.requestInfo.status === "Finalizado" ? "t-button-success" : "t-button-secondary") + ' full--width t-margin-none--right" id="js-status-button" type="button">'+ request.requestInfo.status +'</button>')
                .appendTo(row);
                $('<td>').html('<div class="full justify-content--center"><span class="t-icon-search_icon t-badge-info__icon cursor-pointer" id="js-progression-button"></span></div>').appendTo(row);
                $('<td>').html('<div class="full justify-content--center"><span class="t-icon-column_graphi t-badge-info__icon cursor-pointer" id="js-progression-button"></span></div>').appendTo(row);
                table.append(row);
            }
        });
        if (!found) {
            let row = $('<tr>').addClass('');
            let infoAlert = $('<td colspan="5">').html('<div class="t-badge-info"><span class="t-info_positive t-badge-info__icon"></span> Não existe solicitação relativa a esse alimento </div>');
            infoAlert.appendTo(row);

            table.append(row);
        }
    }
}

function renderFoodsTable(foodsRelation) {
    let table = $('#foodsTable');
    table.empty();

    let head = $('<tr>').addClass('');
    $('<th>').text('Nome').appendTo(head);
    $('<th>').text('Quantidade').appendTo(head);
    $('<th>').text('Unidade').appendTo(head);
    $('<th>').text('').appendTo(head);

    table.append(head);

    $.each(foodsRelation, function(index, food) {
        let row = $('<tr>').addClass('');
        $('<td>').text(food.foodName).appendTo(row);
        $('<td>').text(food.amount).appendTo(row);
        $('<td>').text(food.measurementUnit).appendTo(row);
        $('<td>').html('<div class="justify-content--end"><span class="t-icon-close t-icon" data-foodId="'+ index +'" id="remove-food-button"></span></div>').appendTo(row);

        table.append(row);
    });
}

function amountCalculation(existingAmount, amount, existingUnit, measurementUnit) {
    existingAmount = parseFloat(existingAmount);
    amount = parseFloat(amount);
    let finalAmount = 0;
    if(measurementUnit == "Kg" && existingUnit == "g") {
        finalAmount = existingAmount + (amount/1000);
    } else if (measurementUnit == "g" && existingUnit == "Kg") {
        finalAmount = existingAmount + (amount*1000);
    } else {
        finalAmount = existingAmount + amount
    }
    return finalAmount.toFixed(2);
}
