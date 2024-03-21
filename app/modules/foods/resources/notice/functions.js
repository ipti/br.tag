
let url = new URL(window.location.href);
let noticeID = url.searchParams.get('id');

$(".js-add-notice-item").on("click", function () {
    let selectFood = $("select.js-taco-foods")
    let yearAmount = $(".js-notice-year-amount").val()
    let itemmMeasurement = $("select.js-item-measurement").val()
    let itemDescription = $(".js-item-description").val()
    if (selectFood != "" && yearAmount != "" && itemmMeasurement != "") {
        let name = selectFood.find('option:selected').text().replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');

        data.push([
            name,
            yearAmount,
            itemmMeasurement,
            itemDescription,
            selectFood.val()
        ])
        table.destroy();
        table = $('table').DataTable({
            data: data,
            ordering: true,
            searching: false,
            paginate: true,
            language: getLanguagePtbr(),
            columnDefs: [
                {
                    targets: -1, // Última coluna
                    data: null,
                    defaultContent: '<a class="delete-btn" style="color:#d21c1c; font-size:25px; cursor:pointer;"><span class="t-icon-trash"></span></a>'
                }
            ]
        });
    }
})
$(".js-submit").on("click", function () {
    let transformedData = [];
    data.forEach(item => {
        transformedData.push({
            'name': item[0],
            'yearAmount': item[1],
            'measurement': item[2],
            'description': item[3],
            'food_fk': item[4]
        });
    });

    let notice = {
        name: $(".js-notice-name").val(),
        date: $(".js-date").val(),
        noticeItems: transformedData,
    }

    if (noticeID) {
        $.ajax({
            url: `?r=foods/foodnotice/update&id=${noticeID}`,
            type: "POST",
            data: {
                notice: notice
            }
        }).success(function (response) {
            window.location.href = "?r=foods/foodnotice/index";
        })
        return;
    }
    $.ajax({
        url: "?r=foods/foodnotice/create",
        type: "POST",
        data: {
            notice: notice
        }
    }).success(function (response) {
         window.location.href = "?r=foods/foodnotice/index";
    })

})
$('table tbody').on('click', 'a.delete-btn', function () {
    let row = table.row($(this).parents('tr'));
    let rowIndex = row.index();
    data.splice(rowIndex, 1)
    table.destroy();
    table = $('table').DataTable({
        data: data,
        ordering: true,
        searching: false,
        paginate: true,
        language: getLanguagePtbr(),
        columnDefs: [
            {
                targets: -1, // Última coluna
                data: null,
                defaultContent: '<a class="delete-btn" style="color:#d21c1c; font-size:25px; cursor:pointer;"><span class="t-icon-trash"></span></a>'
            }
        ]
    });
});
