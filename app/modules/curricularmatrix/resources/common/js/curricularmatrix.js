/**
 * Created by IPTIPC100 on 28/04/2016.
 */
$(document).on("click", "#add-matrix", function (e) {
    e.preventDefault();
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
            // $.fn.yiiGridView.update("matrizgridview");
            window.location.reload();
        } else {
            $(".alert").text(data.message).addClass("alert-error").removeClass("alert-success");
        }
        $(".alert-container").show();
    });
});

$(document).on("click", ".matrix-reuse", function () {
    $("#matrix-reuse-modal").modal("show");
});

$(document).on("click", ".confirm-matrix-reuse", function () {
    $.ajax({
        type: "POST",
        url: "/?r=curricularmatrix/curricularmatrix/matrixReuse",
    }).success(function (data) {
        data = JSON.parse(data);
        $(".alert").text("Matriz Curricular do ano anterior reaproveitada com sucesso!").addClass("alert-success").removeClass("alert-error");
        $.fn.yiiGridView.update("matrizgridview");
        $(".alert-container").show();
    });
});