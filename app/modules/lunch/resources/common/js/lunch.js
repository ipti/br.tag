$(document).on('click', '.button-add-portion', function () {
    var button = $(this);
    var modal = $("#addPortion");
    var mealId = button.data('meal-id');

    modal.find('#meal-id').val(mealId);
    updateMeasureAmount($('#unityDropdown'));
});

$(document).on('click', '.button-remove-portion', function () {
    var button = $(this);
    $.ajax({
        url: "?r=lunch/lunch/removePortion",
        type: "POST",
        data: {
            menu: button.data('menu-id'),
            id: button.data('meal-portion-id')
        }
    }).success(function(data){
        location.reload();
    })
});


$(document).on('click', '.button-change-meal', function () {
    var button = $(this);
    var modal = $("#changeMeal");
    var mealId = button.data('meal-id');
    var tr = button.closest("tr");
    var id = tr.find("#id");
    var restrictions = tr.find("#restrictions");
    var amount = tr.find("#amount");

    modal.find('#Meal_restrictions').val(restrictions.text());
    modal.find('#MenuMeal_amount').val(amount.text());
    modal.find('#meal-id').val(mealId);
});

$(document).on('click', '.button-remove-lunch', function () {
    const id = this.getAttribute("data-lunch-id");
    $.ajax({
        url: "?r=lunch/lunch/lunchDelete",
        type: "POST",
        data: {
            id: id
        }
    }).success(function (data) {
        window.location.href = "?r=lunch/lunch/index";
    });
});


$(document).on("show.bs.modal", "#addPortion", function(){
    $('#is-add-amount').show().children().find("input, select").prop('disabled', false);
    $('#is-new-portion').hide().children().find("input, select").prop('disabled', true);

});

$(document).on('click', '#new-portion', function(){
    $('#is-add-amount').hide().children().find("input, select").prop('disabled', true);
    $('#is-new-portion').show().children().find("input, select").prop('disabled', false);
});

$(document).ready(function(){
    $(document).on('change', "#unityDropdown", function(){
       updateMeasureAmount($(this));
    })
})

$(document).ready(function(){
    $(document).on('change', '#foodAmount', function () {
        updateMeasureAmount($('#unityDropdown'));
      })
})

function updateMeasureAmount(select){
    let unityMeasure = select.val();
    $.ajax({
        url: "?r=lunch/lunch/getUnityMeasure",
        type: "POST",
        data: {
            id: unityMeasure
        }
    }).success(function(data) {
        data = JSON.parse(data);
        const amountUnity = $('#foodAmount').val();
        const totalAmount = amountUnity * data.value;
        const txt = `<span>${totalAmount} ${data.measure}</span>`;
        $(txt).replaceAll("#lunchUnityMeasure span");

        const measureInput = $('#measureInput');
        measureInput.val(totalAmount);
    });
}
