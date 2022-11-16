/**
 * Created by IPTIPC100 on 28/04/2016.
 */
$(document).on("click", "#add-matrix", function () {
    var stages = $("#stages").val();
    var disciplines = $("#disciplines").val();
    var workload = $("#workload").val();
    var credits = $("#credits").val();
    var frequencies = $("#frequencies").val();

    $.ajax({
        type: "POST",
        url: addMatrix,
        data: {
            stages: stages,
            disciplines: disciplines,
            frequencies: frequencies,
            workload: workload,
            credits: credits
        }
    }).success(function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $(".alert").text(data.message).addClass("alert-success").removeClass("alert-error");
            $.fn.yiiGridView.update("matrizgridview");
        } else {
            $(".alert").text(data.message).addClass("alert-error").removeClass("alert-success");
        }
        $(".alert-container").show();
    });
});