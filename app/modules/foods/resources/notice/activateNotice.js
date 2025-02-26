$(document).on("click", "#js-change-notice-status", function () {
    let id = $(this).attr('data-noticeId');
    let status = $(this).attr('data-noticeStatus');
    console.log(id, status)

    $.ajax({
        type: 'POST',
        url: "?r=foods/foodnotice/toggleNoticeStatus",
        cache: false,
        data: {
            id: id,
            status: status,
        }
    }).success(function(response) {
        window.location.href = "?r=foods/foodnotice/activateNotice";
    });
});
