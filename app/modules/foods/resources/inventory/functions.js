function renderSelectedFoods(foodsOnStock) {
    let foodsStockDiv = document.getElementById("foods_stock");
    foodsStockDiv.innerHTML = '';

    foodsOnStock.forEach(function(food, index) {
        let stock = `
        <div class="mobile-row t-list-content show--tabletDesktop" id="food_stock_${index}">
            <div class="column is-two-fifths clearfix">${food.foodDescription}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix">${food.amount}</div>
            <div class="column is-one-fifth clearleft--on-mobile clearfix">${food.measurementUnit}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix">${food.expiration_date}</div>
            <div class="column is-one-fifth clearleft--on-mobile clearfix justify-content--end">
                <span class="t-icon-close t-icon" id="stock_button" data-buttonId="${index}"></span>
            </div>
        </div>

        <div class="row t-list-content show--mobile" id="food_stock_${index}">
            <div class="column is-one-fifth clearleft--on-mobile clearfix justify-content--end">
                <span class="t-icon-close t-icon" id="stock_button" data-buttonId="${index}"></span>
            </div>
            <div class="mobile-row"><label>Item:</label>${food.foodDescription}</div>
            <div class="mobile-row"><label>Quantidade:</label>${food.amount}</div>
            <div class="mobile-row"><label>Unidade:</label>${food.measurementUnit}</div>
            <div class="mobile-row"><label>Validade:</label>${food.expiration_date}</div>
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
    $('<th style="width: 18%">').text('Status').appendTo(head);
    $('<th>').text('Entrada/Saída').appendTo(head);

    table.append(head);

    if (typeof id === 'undefined') {
        $.each(foodsOnStock, function(index, stock) {
            table.append(renderStockTableRow(stock));
        });
    } else {
        let found = false;
        $.each(foodsOnStock, function(index, stock) {
            if (stock.foodId == id) {
                found = true;
                table.append(renderStockTableRow(stock));
            }
        });

        if (!found) {
            let row = $('<tr>').addClass('');
            let infoAlert = $('<td colspan="5">').html('<div class="t-badge-info"><span class="t-info_positive t-badge-info__icon"></span> Esse alimento não está no estoque </div>');
            infoAlert.appendTo(row);

            table.append(row);
        }
    }
};

function renderStockTableRow(stock) {
    let row = $('<tr>').addClass('');
    let foodDescription = stock.description;
    foodDescription = foodDescription.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
    let measurementUnit = stock.measurementUnit !== null ? (" (" + stock.measurementUnit + ") ") : "";
    let statusValue;

    switch (stock.status) {
        case "Disponivel":
            statusValue = "Disponível";
            break;
        case "Emfalta":
            statusValue = "Em Falta";
            break;
        default:
            statusValue = "Acabando";
    }

    $('<td>').text(foodDescription).appendTo(row);
    $('<td>').text(stock.amount + measurementUnit).appendTo(row);
    $('<td>').text(stock.expiration_date).appendTo(row);
    if(stock.status == "Emfalta") {
        $('<td style="padding-right: 25px">').html('<button disabled class="t-button-quaternary full--width t-margin-none--right" id="js-status-button" type="button" data-foodStatus="' + stock.status + '" data-foodInventoryId="' + stock.id + '" data-amount="'+ stock.amount +'">'+ statusValue +'</button>').appendTo(row);
    } else {
    $('<td style="padding-right: 25px">').html('<button class="t-button-secondary full--width t-margin-none--right" id="js-status-button" type="button" data-foodStatus="' + stock.status + '" data-foodInventoryId="' + stock.id + '" data-amount="'+ stock.amount +'"><span class="t-icon-pencil text-color--ink"></span>'+ statusValue +'</button>').appendTo(row);
    }
    $('<td>').html('<button id="js-movements-button" type="button" class="t-button-secondary" data-foodInventoryFoodId="' + stock.foodId + '" data-foodInventoryFoodName="'  + foodDescription + '"><span class="t-icon-cart-arrow-down cursor-pointer text-color--ink"></span>Movimentações</button>').appendTo(row);

    return row;
};

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
};

function getFoodInventory() {
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodinventory/getFoodInventory",
        cache: false
    }).success(function(response) {
        food_inventory = JSON.parse(response);
        food_inventory.sort((a, b) => b.amount - a.amount);
        renderStockTable(food_inventory);
        renderStockList(food_inventory);
    })
};

function renderStockList(foodsOnStock, id) {
    let foodStockList = document.getElementById("foodStockList");
    foodStockList.innerHTML = '';

    if (typeof id === 'undefined') {
        $.each(foodsOnStock, function(index, stock) {
            foodStockList.innerHTML += renderStockListRow(stock);
        });
    } else {
        let found = false;

        $.each(foodsOnStock, function(index, stock) {
            if (stock.foodId == id) {
                found = true;
                foodStockList.innerHTML += renderStockListRow(stock);
            }
        });
        if (!found) {
            let foodStock = '<div class="t-badge-info"><span class="t-info_positive t-badge-info__icon"></span> Esse alimento não está no estoque </div>';
            foodStockList.innerHTML += foodStock;
        }
    }
};

function renderStockListRow(stock) {
    let foodDescription = stock.description;
    foodDescription = foodDescription.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
    let measurementUnit = stock.measurementUnit !== null ? (" (" + stock.measurementUnit + ") ") : "";
    let statusValue;

    switch (stock.status) {
        case "Disponivel":
            statusValue = "Disponível";
            break;
        case "Emfalta":
            statusValue = "Em Falta";
            break;
        default:
            statusValue = "Acabando";
    }

    let foodStock = `
    <div class="row no-gap t-list-primary">
        <div class="mobile-row">
                <label class="t-margin-small--right text-bold">Item:</label>
                ${foodDescription}
        </div>
        <div class="mobile-row">
            <div class="column is-half clearfix">
                <div class="mobile-row">
                    <label class="t-margin-small--right text-bold">Quantidade:</label>
                    ${stock.amount + measurementUnit}
                </div>
            </div>
            <div class="column is-half">
                <div class="mobile-row">
                    <label class="t-margin-small--right text-bold">Validade:</label>
                    ${stock.expiration_date}
                </div>
            </div>
        </div>
        <hr class="t-separator-primary">
        <div class="mobile-row">
            <div class="column is-half clearfix">
                <label class="text-bold">Status:</label>
        `;
    if(stock.status == "Emfalta") {
        foodStock += `<button disabled class="t-button-quaternary t-margin-none--right" id="js-status-button" type="button" data-foodStatus="${stock.status}" data-foodInventoryId="${stock.id}" data-amount="${stock.amount}">${statusValue}</button>`;
    } else {
        foodStock += `<button class="t-button-secondary t-margin-none--right" id="js-status-button" type="button" data-foodStatus="${stock.status}" data-foodInventoryId="${stock.id}" data-amount="${stock.amount}"><span class="t-icon-pencil text-color--ink"></span>${statusValue}</button>`;
    }
    foodStock += `
            </div>
            <div class="column is-half">
                <label class="text-bold">Entrada/Saída:</label>
                <button id="js-movements-button" type="button" class="t-button-secondary" data-foodInventoryFoodId="${stock.foodId}" data-foodInventoryFoodName="${foodDescription}"><span class="t-icon-cart-arrow-down cursor-pointer text-color--ink"></span>Movimentações</button>
            </div>
        </div>
    </div>
    `;

    return foodStock;
};
