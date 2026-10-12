(function ($) {
    function addItemRow() {
        var container = $('.js-quotation-items-rows');
        var index = parseInt(container.attr('data-next-index'), 10) || 0;
        var template = $('#quotation-item-template').html();
        var row = $(template.replace(/__index__/g, index));

        container.append(row);
        container.attr('data-next-index', index + 1);

        if (window.jQuery && $.fn.select2 && row.find('select.select-search-on').length) {
            row.find('select.select-search-on').select2({ width: 'resolve' });
        }
    }

    $(document).on('click', '.js-quotation-add-item', function () {
        addItemRow();
    });

    $(document).on('click', '.js-quotation-remove-item', function () {
        $(this).closest('.js-quotation-item-row').remove();
    });
})(jQuery);
