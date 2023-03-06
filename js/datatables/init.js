$(document).ready(function () {
    if ($(".js-tag-table").length) {
        $.getScript('themes/default/js/datatablesptbr.js', function () {
            $(".js-tag-table").dataTable({
                language: getLanguagePtbr(),
                responsive: true
            });
        });
    }
});