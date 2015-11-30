$(document).on("show.bs.modal", "#addItem", function(){
    $('#is-add-amount').show().children().find("input, select").prop('disabled', false);
    $('#is-new-item').hide().children().find("input, select").prop('disabled', true);
});

$(document).on('click', '#new-item', function(){
    $('#is-add-amount').hide().children().find("input, select").prop('disabled', true);
    $('#is-new-item').show().children().find("input, select").prop('disabled', false);

});