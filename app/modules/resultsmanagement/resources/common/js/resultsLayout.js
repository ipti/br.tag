$(document).ready(function () {
    $('div.row-info').each(function () {
        var color;
        if ($(this).hasClass("water")) {
            color = "blue";
        } else if ($(this).hasClass("electricity")) {
            color = "yellow";
        } else if ($(this).hasClass("sewage")) {
            color = "brown";
        } else if ($(this).hasClass("garbage")) {
            color = "green";
        }
        $(this).find(".box").each(function () {
            var percent = $(this).clone().children().remove().end().text().replace("%", "");
            $(this).addClass("box-" + color + "-" + Math.ceil((percent == 0 ? 1 : percent) / 25));
        });
    });
});