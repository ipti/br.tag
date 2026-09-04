let isNutritionist = null

let STOCK_STATUS_ORDER = { 'Disponivel': 0, 'Acabando': 1, 'Emfalta': 2 };

function getStockItemDisplayName(stock) {
    return stock.description.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '').trim();
}

function compareByStatusThenName(a, b) {
    let statusDiff = STOCK_STATUS_ORDER[a.status] - STOCK_STATUS_ORDER[b.status];
    if (statusDiff !== 0) {
        return statusDiff;
    }

    return getStockItemDisplayName(a).localeCompare(getStockItemDisplayName(b), 'pt-BR');
}

$.ajax({
    type:'POST',
    url:'?r=foods/foodinventory/getIsNutritionist'
}).success(function(response) {
    isNutritionist = response;
    console.log(isNutritionist)
})
function renderSelectedFoods(foodsOnStock) {
    let foodsStockDiv = document.getElementById("foods_stock");
    foodsStockDiv.innerHTML = '';

    foodsOnStock.forEach(function(food, index) {
        let stock = `
        <div class="mobile-row t-list-content show--tabletDesktop" id="food_stock_${index}">
            <div class="column is-one-fifth clearfix">${food.foodDescription}</div>
            <div class="column is-one-fifth clearleft--on-mobile clearfix">${food.supplier || ''}</div>
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
            <div class="mobile-row"><label>Fornecedor:</label>${food.supplier || ''}</div>
            <div class="mobile-row"><label>Quantidade:</label>${food.amount}</div>
            <div class="mobile-row"><label>Unidade:</label>${food.measurementUnit}</div>
            <div class="mobile-row"><label>Validade:</label>${food.expiration_date}</div>
        </div>
        `;

        foodsStockDiv.innerHTML += stock;
    });
};

function renderStockTable(foodsOnStock, id, status) {
    let table = $('#foodStockTable');
    table.empty();

    let stockTotals = computeStockTotals(foodsOnStock);

    let head = $('<tr>').addClass('');
    $('<th>').text('Item').appendTo(head);
    $('<th>').text('Fornecedor').appendTo(head);
    $('<th>').text('Quantidade').appendTo(head);
    $('<th>').text('Validade').appendTo(head);
    $('<th>').text('Total em Estoque').appendTo(head);
    $('<th style="width: 18%">').text('Status').appendTo(head);
    $('<th>').text('Entrada/Saída').appendTo(head);

    table.append(head);

    let found = false;

    $.each(foodsOnStock, function(index, stock) {
        if ((typeof id === 'undefined' || stock.foodId == id) &&
            (typeof status === 'undefined' || stock.status == status)) {
            found = true;
            table.append(renderStockTableRow(stock, stockTotals));
        }
    });

    if (!found) {
        let row = $('<tr>').addClass('');
        let infoAlert = $('<td colspan="7">').html('<div class="t-badge-info"><span class="t-info_positive t-badge-info__icon"></span> Esse alimento não está no estoque </div>');
        infoAlert.appendTo(row);
        table.append(row);
    }
}


function buildExpirationWarningBadge(stock) {
    if (!stock.isNearExpiration || stock.status === "Emfalta") {
        return "";
    }

    if (stock.daysUntilExpiration < 0) {
        let daysExpired = Math.abs(stock.daysUntilExpiration);
        let message = "Item vencido há " + daysExpired + (daysExpired === 1 ? " dia" : " dias");

        return '<span class="t-badge-critical clearfix t-badge-critical--inline" data-toggle="tooltip" data-placement="right" title="' + message + '">Vencido</span>';
    }

    let message = "Produto próximo de vencer (" + stock.daysUntilExpiration + (stock.daysUntilExpiration === 1 ? " dia)" : " dias)");

    return '<span class="t-badge-warning clearfix t-badge-warning--inline" data-toggle="tooltip" data-placement="right" title="' + message + '">Vence em breve</span>';
}

function initExpirationTooltips() {
    $('[data-toggle="tooltip"]').tooltip({ container: 'body', placement: 'right' });
}

function updateExpirationAlert(foodInventory) {
    let alertBox = $('#expiration-alert');

    // Um lote só conta pro alerta enquanto ainda houver quantidade real
    // dele em estoque (lote já consumido, mesmo sem status atualizado
    // manualmente para "Em falta", não deveria continuar gerando aviso).
    let isInStock = function (stock) {
        return stock.status !== 'Emfalta' && parseFloat(stock.amount) > 0;
    };

    // Se já existe outro lote do mesmo alimento dentro da validade (e
    // ainda em estoque), esse alimento não entra no alerta: o lote seguro
    // cobre a necessidade, mesmo que o lote antigo ainda esteja vencendo.
    let foodsWithSafeLot = {};
    foodInventory.filter(isInStock).forEach(function (stock) {
        if (!stock.isNearExpiration) {
            foodsWithSafeLot[stock.foodId] = true;
        }
    });

    let nearExpirationItems = foodInventory.filter(function (stock) {
        return isInStock(stock) && stock.isNearExpiration && !foodsWithSafeLot[stock.foodId];
    });

    if (nearExpirationItems.length === 0) {
        alertBox.addClass('hide').removeClass('alert-error alert-success').html('');
        return;
    }

    let names = nearExpirationItems
        .map(getStockItemDisplayName)
        .filter(function (name, index, all) {
            return all.indexOf(name) === index;
        });

    let message = (nearExpirationItems.length === 1
        ? '1 lote em estoque está próximo do vencimento ou já venceu: '
        : nearExpirationItems.length + ' lotes em estoque estão próximos do vencimento ou já venceram: ')
        + names.join(', ') + '.';

    alertBox.removeClass('hide alert-error alert-success').html(message);
}

function formatStockAmount(amount) {
    let rounded = Math.round(amount * 100) / 100;
    return String(rounded);
}

// Um lote só entra no total em estoque de um alimento se ainda estiver
// realmente disponível: nem "Em falta" nem já vencido (mesmo que o status
// ainda não tenha sido atualizado manualmente para "Em falta").
function isCountedInStockTotal(stock) {
    let isExpired = stock.daysUntilExpiration !== null && stock.daysUntilExpiration < 0;
    return stock.status !== 'Emfalta' && !isExpired;
}

// Soma a quantidade em estoque por alimento, agrupando por unidade de
// medida em vez de tentar converter entre elas (ex.: Kg não é somado com
// unidade). Retorna um mapa foodId -> { unidade: quantidade }.
function computeStockTotals(foodsOnStock) {
    let totalsByFood = {};

    foodsOnStock.filter(isCountedInStockTotal).forEach(function (stock) {
        let unit = stock.measurementUnit || '';

        if (!totalsByFood[stock.foodId]) {
            totalsByFood[stock.foodId] = {};
        }

        totalsByFood[stock.foodId][unit] = (totalsByFood[stock.foodId][unit] || 0) + parseFloat(stock.amount);
    });

    return totalsByFood;
}

function formatFoodTotal(stockTotals, foodId) {
    let unitTotals = stockTotals[foodId];

    if (!unitTotals) {
        return '0';
    }

    return Object.keys(unitTotals)
        .sort()
        .map(function (unit) {
            return formatStockAmount(unitTotals[unit]) + (unit !== '' ? ' ' + unit : '');
        })
        .join(' + ');
}

function renderStockTableRow(stock, stockTotals) {
    let row = $('<tr>').addClass('');
    let foodDescription = getStockItemDisplayName(stock);
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
    $('<td>').text(stock.supplier || '').appendTo(row);
    $('<td>').text(stock.amount + measurementUnit).appendTo(row);
    $('<td>').html('<div style="display:flex; align-items:center; gap:8px; flex-wrap:nowrap; white-space:nowrap;">' + stock.expiration_date + buildExpirationWarningBadge(stock) + '</div>').appendTo(row);
    $('<td>').text(formatFoodTotal(stockTotals, stock.foodId)).appendTo(row);
    if(stock.status == "Emfalta") {
        $('<td style="padding-right: 25px">').html(`<button disabled class="t-button-quaternary full--width t-margin-none--right" id="js-status-button" ${isNutritionist ? 'disabled': ''} type="button" data-foodStatus="${stock.status}" data-foodInventoryId="${stock.id}" data-amount="${stock.amount}">${statusValue}</button>`).appendTo(row);
    } else {
    $('<td style="padding-right: 25px">').html(`<button class="t-button-secondary full--width t-margin-none--right" id="js-status-button"  ${isNutritionist ? 'disabled': ''} type="button" data-foodStatus="${stock.status}" data-foodInventoryId="${stock.id}" data-amount="${stock.amount}"><span class="t-icon-pencil text-color--ink"></span>${statusValue}</button>`).appendTo(row);
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
    $('<th>').text('Fornecedor').appendTo(head);
    $('<th>').text('Quantidade').appendTo(head);
    $('<th>').text('Data').appendTo(head);

    table.append(head);

    $.each(movements, function(index, foodInventory) {
        let row = $('<tr>').addClass('');
        let measurementUnit = (" (" + foodInventory.measurementUnit + ") ");
        let textColor = foodInventory.type == "Saída" ? 'text-color--red' : 'text-color--green';

        $('<td class="' + textColor + '">').text(foodInventory.type).appendTo(row);
        $('<td>').text(foodName).appendTo(row);
        $('<td>').text(foodInventory.supplier || '-').appendTo(row);
        $('<td>').text(foodInventory.amount + measurementUnit).appendTo(row);
        $('<td>').text(foodInventory.date).appendTo(row);

        table.append(row);
    });
};

function renderStockList(foodsOnStock, id, status) {
    let foodStockList = document.getElementById("foodStockList");
    foodStockList.innerHTML = '';

    let stockTotals = computeStockTotals(foodsOnStock);

    let found = false;

    $.each(foodsOnStock, function(index, stock) {
        if ((typeof id === 'undefined' || stock.foodId == id) &&
            (typeof status === 'undefined' || stock.status == status)) {
            found = true;
            foodStockList.innerHTML += renderStockListRow(stock, stockTotals);
        }
    });

    if (!found) {
        let foodStock = '<div class="t-badge-info"><span class="t-info_positive t-badge-info__icon"></span> Esse alimento não está no estoque </div>';
        foodStockList.innerHTML += foodStock;
    }
};

function renderStockListRow(stock, stockTotals) {
    let foodDescription = getStockItemDisplayName(stock);
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
                <label class="t-margin-small--right text-bold">Fornecedor:</label>
                ${stock.supplier || ''}
        </div>
        <div class="mobile-row">
            <div class="column is-half clearfix">
                <div class="mobile-row">
                    <label class="t-margin-small--right text-bold">Quantidade:</label>
                    ${stock.amount + measurementUnit}
                </div>
            </div>
            <div class="column is-half">
                <div class="mobile-row" style="align-items:center; gap:8px; flex-wrap:wrap;">
                    <label class="t-margin-small--right text-bold">Validade:</label>
                    ${stock.expiration_date}${buildExpirationWarningBadge(stock)}
                </div>
            </div>
        </div>
        <div class="mobile-row">
                <label class="t-margin-small--right text-bold">Total em Estoque:</label>
                ${formatFoodTotal(stockTotals, stock.foodId)}
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
