
let url = new URL(window.location.href);
let noticeID = url.searchParams.get('id');

$(".js-add-notice-item").on("click", function () {
    let selectFood = $("#item_food")
    let yearAmount = $(".js-notice-year-amount").val()
    let itemmMeasurement = $("select.js-item-measurement").val()
    let itemDescription = $(".js-item-description").val()
    if (selectFood != "" && yearAmount != "" && itemmMeasurement != "") {
        let name = selectFood.find('option:selected').text().replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');

        if(yearAmount.indexOf(',') === -1) {
            data.push([
                name,
                yearAmount,
                itemmMeasurement,
                itemDescription,
                selectFood.val()
            ])
            renderFoodsTable();

            $(".js-notice-year-amount").val("");
            $(".js-item-description").val("");
            $("select.js-taco-foods").val("").trigger("change");
            $("select.js-item-measurement").val("").trigger("change");
        } else {
            $('#info-alert').removeClass('hide').addClass('alert-error').html("Quantidade anual informada não é válida, utilize números positivos e se decimal, separe por '.'");
        }
    } else {
        $('#info-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
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
    renderFoodsTable();
});
function renderFoodsTable() {
    table.destroy();
    table = $('table').DataTable({
        data: data,
        ordering: true,
        searching: false,
        paginate: true,
        language: getLanguagePtbr(),
        columnDefs: [
            {
                targets: -1,
                data: null,
                render: function (data, type, row, meta) {
                    return `
                        <a class="update-btn" data-index="${meta.row}" style="font-size:20px; cursor:pointer;">
                            <span class="t-icon-pencil"></span>
                        </a>
                        <a class="delete-btn" style="color:#d21c1c; font-size:25px; cursor:pointer;" data-index="${meta.row}">
                            <span class="t-icon-trash"></span>
                        </a>`;
                }
            }
        ]
    });
}

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
$(document).on("click", ".js-add-shopping-list", function () {
    $.ajax({
        type: 'POST',
        url: "?r=foods/foodnotice/getShoppingList",
        cache: false,
        data: {
        }
    }).success(function(response) {
        let foodData = DOMPurify.sanitize(response);
        let shoppingList = JSON.parse(foodData);
        Object.entries(shoppingList).forEach(function([id, value]) {
            foodName = value.name.replace(/,/g, '').replace(/\b(cru[ao]?)\b/g, '');
            measurementunit = value.measure == "u" ? "Und" : value.measure;
            data.push([
                foodName,
                value.total,
                measurementunit,
                "",
                value.id
            ])
            renderFoodsTable();
        });
        $('#shopping-list').addClass("hide");
    });
});

$(document).on("click", ".js-not-add-shopping-list", function () {
    $('#shopping-list').addClass("hide");
});

$(document).on("click", ".update-btn", function () {
    $("#js-edit-food-items").modal("show");
    let foodTableId = $(this).attr('data-index');

    let foodName = data[foodTableId][0];
    let foodVal = Object.keys(tacoFoods).find(key => tacoFoods[key] === foodName) || "";

    $("#edit_item_food").val(foodVal).trigger("change");
    $("#edit_notice_year_amount").val(data[foodTableId][1]);
    $("#edit_item_measurement").val(data[foodTableId][2]).trigger("change");
    $("#edit_item_description").val(data[foodTableId][3]);

    $("#edit-foods").attr("data-index", foodTableId);
});

$(document).on("click", "#edit-foods", function () {
    let foodTableId = $(this).attr('data-index');
    let yearAmount = $("#edit_notice_year_amount").val();
    let selectFoodText = $("#edit_item_food").find('option:selected').text();
    let selectFood = $("#edit_item_food").val();
    let itemmMeasurement = $("#edit_item_measurement").val();
    let description = $("#edit_item_description").val();

    if (selectFood != "" && yearAmount != "" && itemmMeasurement != "") {
        if($("#edit_notice_year_amount").val().indexOf(',') === -1) {
            data[foodTableId][0] = selectFoodText;
            data[foodTableId][1] = yearAmount;
            data[foodTableId][2] = itemmMeasurement;
            data[foodTableId][3] = description;
            data[foodTableId][4] = selectFood;

            renderFoodsTable();

            $("#js-edit-food-items").modal('hide');
        } else {
            $('#modal-food-alert').removeClass('hide').addClass('alert-error').html("Quantidade anual informada não é válida, utilize números positivos e se decimal, separe por '.'");
        }
    } else {
        $('#modal-food-alert').removeClass('hide').addClass('alert-error').html("Campos obrigatórios precisam ser informados.");
    }


});
