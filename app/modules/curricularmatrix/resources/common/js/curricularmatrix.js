/**
 * Created by IPTIPC100 on 28/04/2016.
 */
$(document).on("click", "#add-matrix", function () {
    var stages = $("#stages").val();
    var disciplines = $("#disciplines").val();
    var workload = $("#workload").val();
    var credits = $("#credits").val();

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
    }).success(function () {
        window.location = window.location;
    });
});