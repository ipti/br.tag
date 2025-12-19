$(document).on("click", ".save", function () {

    var configs = [];
    $(".parameter-structure-container").find(".success-configs").hide();
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
            $(".save-config-loading-gif").show();
        },
    }).success(function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $(".success-configs").html(DOMPurify.sanitize(data.text)).show();
        }
    }).complete(function () {
        $(".save-config-loading-gif").hide();
    });

});