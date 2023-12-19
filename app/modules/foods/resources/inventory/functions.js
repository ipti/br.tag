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
    let table = $('#foodStockTable');
    table.empty();

    let head = $('<tr>').addClass('');
    $('<th>').text('Item').appendTo(head);
    $('<th>').text('Quantidade').appendTo(head);
    $('<th>').text('Validade').appendTo(head);
    $('<th>').text('Movimentações').appendTo(head);
    $('<th>').text('Status').appendTo(head);

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
            $('<td>').html('<span id="js-movements-button" class="t-icon-cart-arrow-down cursor-pointer" data-foodInventoryFoodId="' + stock.foodId + '" data-foodInventoryFoodName="'  + foodDescription + '"></span>').appendTo(row);
            let select = $('<select class="select-search-on t-field-select__input select2-container" id="foodInventoryStatus" name="foodInventoryStatus" data-foodInventoryId="' + stock.id + '" data-amount="'+ stock.amount +'">' +
            '<option value="Disponivel">Disponível</option>'+
            '<option value="Acabando">Acabando</option>'+
            '<option value="Emfalta">Em falta</option>'+
            '</select>');
            select.val(stock.status)
            if (stock.status === 'Emfalta') {
                select.prop('disabled', true);
            }
            $('<td>').html(select).appendTo(row);

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
                $('<td>').html('<span id="js-movements-button" class="t-icon-cart-arrow-down cursor-pointer" data-foodInventoryFoodId="' + stock.foodId + '" data-foodInventoryFoodName="'  + foodDescription + '"></span>').appendTo(row);
                let select = $('<select class="select-search-on t-field-select__input select2-container" id="foodInventoryStatus" name="foodInventoryStatus" data-foodInventoryId="' + stock.id + '" data-amount="'+ stock.amount +'">' +
                '<option value="Disponivel">Disponível</option>'+
                '<option value="Acabando">Acabando</option>'+
                '<option value="Emfalta">Em falta</option>'+
                '</select>');
                select.val(stock.status)
                if (stock.status === 'Emfalta') {
                    select.prop('disabled', true);
                }
                $('<td>').html(select).appendTo(row);

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
function renderMovementsTable(movements, foodName) {
    let table = $('#movementsTable');
    table.empty();

    let head = $('<tr>').addClass('');
    $('<th>').text('Tipo').appendTo(head);
    $('<th>').text('Item').appendTo(head);
    $('<th>').text('Quantidade').appendTo(head);
    $('<th>').text('Data').appendTo(head);

    table.append(head);

    $.each(movements, function(index, foodInventory) {
        let row = $('<tr>').addClass('');
        let measurementUnit = (" (" + foodInventory.measurementUnit + ") ");
        let textColor = foodInventory.type == "Saída" ? 'text-color--red' : 'text-color--green';

        $('<td class="' + textColor + '">').text(foodInventory.type).appendTo(row);
        $('<td>').text(foodName).appendTo(row);
        $('<td>').text(foodInventory.amount + measurementUnit).appendTo(row);
        $('<td>').text(foodInventory.date).appendTo(row);

        table.append(row);
    });
}
function getFoodInventory() {
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodInventory/getFoodInventory",
        cache: false
    }).success(function(response) {
        food_inventory = JSON.parse(response);
        food_inventory.sort((a, b) => b.amount - a.amount);
        renderStockTable(food_inventory);
    })
}
