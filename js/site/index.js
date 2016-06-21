$(document).on("click", ".load-more", function() {
    $.ajax({
        type: 'GET',
        url: loadMoreLogs,
        data: {
            date : $(".log-date").last().text()
        },
        success: function (data) {
            $(".logs").append(data);
            if ($(".log").length >= $(".eggs").find(".widget").attr("total")) {
                $(".load-more").hide();
            }
            $(".log").each(function() {
                $(this).find(".glyphicons").html("<i></i>" + changeNameLength($(this).find(".glyphicons").html(), 100));
            });
        }});
});

$(document).ready(function () {
    $(".log").each(function() {
        $(this).find(".glyphicons").html("<i></i>" + changeNameLength($(this).find(".glyphicons").html(), 100));
    });
});