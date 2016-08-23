/**
 * Created by IPTIPC100 on 28/04/2016.
 */
$(document).on("click", "#add-matrix", function () {
    var stages = $("#stages").val();
    var disciplines = $("#disciplines").val();
    var workload = $("#workload").val();
    var credits = $("#credits").val();
    var frequencies = $("#frequencies").val()
    var school_days = $("#school_days").val()


    $.ajax({
        type: "POST",
        url: addMatrix,
        data: {
            stages: stages,
            disciplines: disciplines,
            frequencies: frequencies,
            school_days: school_days,
            workload: workload,
            credits: credits
        }
    }).success(function () {
        window.location = window.location;
    });
});