function renderFoodsTable(foodsRelation) {
    let table = $('#foodsTable');
    table.empty();

    let head = $('<tr>').addClass('');
    $('<th>').text('Nome').appendTo(head);
    $('<th>').text('Quantidade').appendTo(head);
    $('<th>').text('Unidade').appendTo(head);
    $('<th>').text('').appendTo(head);

    table.append(head);

    $.each(foodsRelation, function(index, stock) {
        let row = $('<tr>').addClass('');
        $('<td>').text(stock.foodDescription).appendTo(row);
        $('<td>').text(stock.amount).appendTo(row);
        $('<td>').text(stock.measurementUnit).appendTo(row);
        $('<td>').html('<div class="justify-content--end"><span class="t-icon-close t-icon" data-foodId="'+ index +'" id="remove-food-button"></span></div>').appendTo(row);

        table.append(row);
    });
}
