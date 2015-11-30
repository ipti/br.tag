$(document).on('click', '.button-add-portion', function () {
    var button = $(this);
    var modal = $("#addPortion");
    var mealId = button.data('meal-id');

    modal.find('#meal-id').val(mealId);
});

$(document).on('click', '.button-remove-portion', function () {
    var button = $(this);
    var modal = $("#removePortion");
    var mealPortionId = button.data('meal-portion-id');

    modal.find('#meal-portion-id').val(mealPortionId);
});



$(document).on("show.bs.modal", "#addPortion", function(){
    $('#is-add-amount').show().children().find("input, select").prop('disabled', false);
    $('#is-new-portion').hide().children().find("input, select").prop('disabled', true);
});

$(document).on('click', '#new-portion', function(){
    $('#is-add-amount').hide().children().find("input, select").prop('disabled', true);
    $('#is-new-portion').show().children().find("input, select").prop('disabled', false);
});