$(document).on("show.bs.modal", "#addItem", function(){
    $('#is-add-amount').show().children().find("input, select").prop('disabled', false);
    $('#is-new-item').hide().children().find("input, select").prop('disabled', true);
});

$(document).on('click', '#new-item', function(){
    $('#is-add-amount').hide().children().find("input, select").prop('disabled', true);
    $('#is-new-item').show().children().find("input, select").prop('disabled', false);

});
$(document).on('click','#js-removeItem', function(){
    var lunchItemId = $(this).attr("data-id");
    $.ajax({
        url:'?r=lunch/stock/deleteitem',
        type:'POST',
        data: {
            lunchItemId: lunchItemId
        }, 
        beforeSend: function() {
            $(".js-removeItem").prop("disabled", true);
            $(".js-change-cursor").css("cursor", " not-allowed");
            $(".js-disabled-table").css("opacity", 0.5);
        }
    }).complete(function () {
        $(".js-removeItem").prop("disabled", false);
        $(".js-change-cursor").css("cursor", "pointer");
        $(".js-disabled-table").css("opacity", 1.0);
    }).success({ function(data) {
       
    }})
});