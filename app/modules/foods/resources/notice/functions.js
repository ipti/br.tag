
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
    let noticePdfFile = $(".js-notice_pdf")[0].files[0];

    let notice = {
        name: $(".js-notice-name").val(),
        date: $(".js-date").val(),
        noticeItems: transformedData,
    };

    let formData = new FormData();
    formData.append("noticePdf", noticePdfFile);
    formData.append("notice", JSON.stringify(notice));

    if (noticeID) {
        $.ajax({
            url: `?r=foods/foodnotice/update&id=${noticeID}`,
            type: "POST",
            data : formData,
            contentType : false,
            processData : false
        }).success(function (response) {
            window.location.href = "?r=foods/foodnotice/index";
        })
        return;
    }
    $.ajax({
        url: "?r=foods/foodnotice/create",
        type: "POST",
        data : formData,
        contentType : false,
		processData : false
    }).success(function (response) {
         window.location.href = "?r=foods/foodnotice/index";
    }).error(function (jqXHR, textStatus, errorThrown) {
        console.error("Erro: " + textStatus, errorThrown);
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

$(document).on("change", ".js-notice_pdf", function(e) {
    $(".uploaded-notice-name").text(e.target.files[0].name);
});

$(document).on("click", "#js-view-pdf", function () {
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodnotice/getNoticePdfUrl",
        cache: false,
        data: {
            id: noticeID,
        }
    }).success(function(response) {
        let data = DOMPurify.sanitize(response);
        let url = JSON.parse(data);

        if (url.error) {
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Não foi possível acessar a URL do PDF.");
        } else {
            window.open(url, '_blank');
        }
    });
});
