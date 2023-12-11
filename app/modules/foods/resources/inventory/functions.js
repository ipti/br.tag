function renderSelectedFoods(foodsOnStock) {
    let foodsStockDiv = document.getElementById("foods_stock");
    foodsStockDiv.innerHTML = '';

    foodsOnStock.forEach(function(food, index) {
        let stock = `
        <div class="mobile-row t-list-content" id="food_stock_${index}">
            <div class="column is-two-fifths clearfix">${food.foodDescription}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix">${food.amount}</div>
            <div class="column is-one-fifth clearleft--on-mobile clearfix">${food.measurementUnit}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix">${food.expiration_date}</div>
            <div class="column is-one-fifth clearleft--on-mobile clearfix justify-content--end">
                <span class="t-icon-close t-icon" id="stock_button" data-buttonId="${index}"></span>
            </div>
        </div>
        `;

        foodsStockDiv.innerHTML += stock;
    });
};

function renderStockTable(foodsOnStock, id) {
    let table = $('.tag-table-secondary');
    table.empty();

    let head = $('<tr>').addClass('');
    $('<th>').text('Item').appendTo(head);
    $('<th>').text('Quantidade').appendTo(head);
    $('<th>').text('Validade').appendTo(head);
    $('<th>').text('Em falta').appendTo(head);
    $('<th>').text('Movimentações').appendTo(head);

    table.append(head);

    if (typeof id === 'undefined') {
        $.each(foodsOnStock, function(index, stock) {
            let row = $('<tr>').addClass('');
            let foodDescription = stock.description;
            foodDescription = foodDescription.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            let measurementUnit = stock.measurementUnit !== null ? (" (" + stock.measurementUnit + ") ") : "";

            $('<td>').text(foodDescription).appendTo(row);
            $('<td>').text(stock.amount + measurementUnit).appendTo(row);
            $('<td>').text(stock.expiration_date).appendTo(row);
            let checkboxInput = $('<td>').html('<input type="checkbox" id="spent-checkbox" data-foodInventoryId="'+ stock.id +'" data-amount="'+ stock.amount +'" ' + (stock.spent ? 'checked' : '') + '> Em falta');
            $(checkboxInput).appendTo(row);
            $('<td>').html('<span id="js-movements-button" class="t-icon-cart-arrow-down cursor-pointer" data-foodInventoryId="' + stock.id + '"></span>').appendTo(row);

            table.append(row);
        });
    } else {
        let found = false;
        $.each(foodsOnStock, function(index, stock) {
            if (stock.foodId == id) {
                found = true;
                let row = $('<tr>').addClass('');
                let foodDescription = stock.description;
                foodDescription = foodDescription.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
                let measurementUnit = stock.measurementUnit !== null ? (" (" + stock.measurementUnit + ") ") : "";

                $('<td>').text(foodDescription).appendTo(row);
                $('<td>').text(stock.amount + measurementUnit).appendTo(row);
                $('<td>').text(stock.expiration_date).appendTo(row);
                let checkboxInput = $('<td>').html('<input type="checkbox" id="spent-checkbox" data-foodInventoryId="'+ stock.id +'" data-amount="'+ stock.amount +'" ' + (stock.spent ? 'checked' : '') + '> Em falta');
                $(checkboxInput).appendTo(row);
                $('<td>').html('<span class="t-icon-cart-arrow-down"></span>').appendTo(row);

                table.append(row);
            }
        });

        if (!found) {
            let row = $('<tr>').addClass('');
            let infoAlert = $('<td colspan="5">').html('<div class="t-badge-info"><span class="t-info_positive t-badge-info__icon"></span> Esse alimento não está no estoque </div>');
            infoAlert.appendTo(row);

            table.append(row);
        }
    }


}
