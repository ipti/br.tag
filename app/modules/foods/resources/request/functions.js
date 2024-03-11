function renderSelectedRequests(foodRequests) {
    let foodRequestsDiv = document.getElementById("food_request");
    foodRequestsDiv.innerHTML = '';

    foodRequests.forEach(function(request, index) {
        let list = `
        <div class="mobile-row t-list-content show--tabletDesktop" id="request_list_${index}">
            <div class="column is-third clearfix">${request.foodDescription}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix">${request.amount}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix">${request.measurementUnit}</div>
            <div class="column is-two-fifths clearleft--on-mobile clearfix">${request.description}</div>
            <div class="column is-one-tenth clearleft--on-mobile clearfix justify-content--end">
                <span class="t-icon-close t-icon" id="request_button" data-buttonId="${index}"></span>
            </div>
        </div>
        <div class="row t-list-content show--mobile" id="request_list_${index}">
            <div class="column is-one-tenth clearleft--on-mobile clearfix justify-content--end">
                <span class="t-icon-close t-icon" id="request_button" data-buttonId="${index}"></span>
            </div>
            <div class="mobile-row"><label>Item:</label>${request.foodDescription}</div>
            <div class="mobile-row"><label>Quantidade:</label>${request.amount}</div>
            <div class="mobile-row"><label>Unidade:</label>${request.measurementUnit}</div>
            <div class="mobile-row"><label>Descrição:</label>${request.description}</div>

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
    $('<th>').text('Status').appendTo(head);

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
            $('<td style="padding-right: 25px">').html('<button class="t-button-secondary full--width t-margin-none--right" id="js-status-button" type="button"><span class="t-icon-pencil text-color--ink"></span>'+ request.status +'</button>').appendTo(row);

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
