$(document).on("click", "#js-change-farmer-status", function () {
    let id = $(this).attr('data-farmerId');
    let status = $(this).attr('data-farmerStatus');
    console.log(id, status)

    $.ajax({
        type: 'POST',
        url: "?r=foods/farmerregister/toggleFarmerStatus",
        cache: false,
        data: {
            id: id,
            status: status,
        }
    }).success(function(response) {
        window.location.href = "?r=foods/farmerregister/activateFarmers";
    });
});
