$(document).on("click", ".save", function () {

    var configs = [];
    $(".parameter-row").each(function () {
        configs.push({
            id: $(this).find(".parameter-id").val(),
            value: $(this).find(".parameter-value").val(),
        });
    });

    $.ajax({
        url: "?r=admin/editInstanceConfigs",
        type: "POST",
        data: {
            configs: configs,
        },
        beforeSend: function () {
        },
    }).success(function (data) {
        data = JSON.parse(data);
        if (data.valid) {
        } else {
            $(".error-configs").html(DOMPurify.sanitize(data.error)).show();
        }
    }).complete(function () {
    });

});