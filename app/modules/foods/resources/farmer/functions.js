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
