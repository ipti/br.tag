function renderSelectedRequests(foodRequests) {
    let foodRequestsDiv = document.getElementById("food_request");
    foodRequestsDiv.innerHTML = '';

    foodRequests.forEach(function(request, index) {
        let list = `
        <div class="mobile-row t-list-content" id="request_list_${index}">
            <div class="column is-third clearfix">${request.foodDescription}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix">${request.amount}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix">${request.measurementUnit}</div>
            <div class="column is-two-fifths clearleft--on-mobile clearfix">${request.description}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix justify-content--end">
                <span class="t-icon-close t-icon" id="request_button" data-buttonId="${index}"></span>
            </div>
        </div>
        `;

        foodRequestsDiv.innerHTML += list;
    });
};

function renderRequestTable(foodRequests, id) {
    let table = $('#foodRequestTable');
    table.empty();

    let head = $('<tr>').addClass('');
    $('<th>').text('Item').appendTo(head);
    $('<th>').text('Quantidade').appendTo(head);
    $('<th>').text('Data de solicitação').appendTo(head);
    $('<th>').text('Descrição').appendTo(head);
    // $('<th>').text('Confirmar Entrega').appendTo(head);

    table.append(head);

    if (typeof id === 'undefined') {
        $.each(foodRequests, function(index, request) {
            let row = $('<tr>').addClass('');
            let foodName = request.foodName;
            foodName = foodName.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            let measurementUnit = request.measurementUnit !== null ? (" (" + request.measurementUnit + ") ") : "";

            $('<td>').text(foodName).appendTo(row);
            $('<td>').text(request.amount + measurementUnit).appendTo(row);
            $('<td>').text(request.date).appendTo(row);
            $('<td>').text(request.description).appendTo(row);
            // let checkboxInput = $('<td>').html('<input type="checkbox" id="delivery-checkbox" data-foodRequestId="'+ request.id +'" ' + (request.delivered ? 'checked disabled' : '') + '>');
            // $(checkboxInput).appendTo(row);

            table.append(row);
        });
    } else {
        let found = false;
        $.each(foodRequests, function(index, request) {
            if (request.foodId == id) {
                found = true;
                let row = $('<tr>').addClass('');
                let foodName = request.foodName;
                foodName = foodName.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
                let measurementUnit = request.measurementUnit !== null ? (" (" + request.measurementUnit + ") ") : "";

                $('<td>').text(foodName).appendTo(row);
                $('<td>').text(request.amount + measurementUnit).appendTo(row);
                $('<td>').text(request.date).appendTo(row);
                $('<td>').text(request.description).appendTo(row);
                // let checkboxInput = $('<td>').html('<input type="checkbox" id="delivery-checkbox" data-foodRequestId="'+ request.id +'" ' + (request.delivered ? 'checked disabled' : '') + '>');
                // $(checkboxInput).appendTo(row);


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
