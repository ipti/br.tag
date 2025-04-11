function renderFoodsTable(foodsRelation) {
    let table = $('#foodsTable');
    table.empty();

    let head = $('<tr>').addClass('');
    $('<th>').text('Nome').appendTo(head);
    $('<th>').text('Quantidade').appendTo(head);
    $('<th>').text('Unidade').appendTo(head);
    $('<th>').text('Edital').appendTo(head);
    $('<th>').text('').appendTo(head);

    table.append(head);

    $.each(foodsRelation, function(index, food) {
        let row = $('<tr>').addClass('');
        $('<td>').text(food.foodDescription).appendTo(row);
        $('<td>').text(food.amount).appendTo(row);
        $('<td>').text(food.measurementUnit).appendTo(row);
        $('<td>').text(food.notice).appendTo(row);
        $('<td>').html('<div class="justify-content--end"><span class="t-icon-close t-icon" data-foodId="'+ index +'" id="remove-food-button"></span></div>').appendTo(row);

        table.append(row);
    });
}

function renderAcceptedFoodsTable (farmerAcceptedFoods) {
    let $requestAcceptedItems;
    if(!farmerAcceptedFoods || farmerAcceptedFoods.length === 0) {
        $requestAcceptedItems = `
            <div class="row">
                <div class="t-badge-info t-margin-none--left">
                    <span class="t-info_positive"></span> Nenhum alimento foi aceito por este agricultor
                </div>
            </div>
        `;
    } else {
        $requestAcceptedItems = `
        <div class="row">
        <div class="column clearfix">
        <table id="requestAcceptedItemsTable"  aria-describedby="requestAcceptedItemsTable" class="tag-table-secondary align-start no-margin">
            <tr>
                <th>Solicitação</th>
                <th>Alimento</th>
                <th>Quantidade</th>
                <th>Data</th>
            </tr>
        `;
        $.each(farmerAcceptedFoods, function(index, item) {
            $requestAcceptedItems += `
                <tr>
                    <td>${item.request}</td>
                    <td>${item.foodName.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '').trim()}</td>
                    <td>${item.amount} (${item.measurementUnit})</td>
                    <td>${item.date}</td>
                </tr>
            `;
        });
        $requestAcceptedItems += `
            </table>
            </div>
            </div>
        `;
    }
    $("#requestAcceptedItems").html($requestAcceptedItems);
}

function renderDeliveredFoodsTable (farmerDeliveredFoods) {
    let $requestDeliveredItems;
    if(!farmerDeliveredFoods || farmerDeliveredFoods.length === 0) {
        $requestDeliveredItems = `
            <div class="row">
                <div class="t-badge-info t-margin-none--left">
                    <span class="t-info_positive"></span> Nenhum alimento foi entregue por este agricultor
                </div>
            </div>
        `;
    } else {
        $requestDeliveredItems = `
        <div class="row">
        <div class="column clearfix">
        <table id="requestDeliveredItemsTable"  aria-describedby="requestDeliveredItemsTable" class="tag-table-secondary align-start no-margin">
            <tr>
                <th>Solicitação</th>
                <th>Alimento</th>
                <th>Quantidade</th>
                <th>Data</th>
            </tr>
        `;
        $.each(farmerDeliveredFoods, function(index, item) {
            $requestDeliveredItems += `
                <tr>
                    <td>${item.request}</td>
                    <td>${item.foodName.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '').trim()}</td>
                    <td>${item.amount} (${item.measurementUnit})</td>
                    <td>${item.date}</td>
                </tr>
            `;
        });
        $requestDeliveredItems += `
            </table>
            </div>
            </div>
        `;
    }
    $("#requestDeliveredItems").html($requestDeliveredItems);
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
