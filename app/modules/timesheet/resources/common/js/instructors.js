$("#instructor_fk").on("change", function () {
    $.ajax({
        'url': getInstructorsDiscipinesURL + "/" + $(this).val(),
        'method': 'get'
    }).success(function(data){
        data = $.parseJSON(data);
        console.log(data);
    });
});

$(document).on("click","#add-instructors-button",function(){
    $("#add-instructors-form").submit();
});
$(document).on("click","#add-instructors-unavailability-button", function(){
    $("#add-instructors-unavailability-form").submit();
});

$(document).on("click", "#add-unavailability", function(){
    var html = $("#add-instructors-unavailability-times_0")[0].outerHTML
        .replace(/_0/g, "_"+$(".add-instructors-unavailability-times").length)
        .replace(/\[0]/g, "["+$(".add-instructors-unavailability-times").length+"]");

    var last = $("#add-instructors-unavailability-times").children().last();
    $("#add-instructors-unavailability-times").children().last().remove();
    $("#add-instructors-unavailability-times").append(html);
    $("#add-instructors-unavailability-times").append(last);
    $("#add-instructors-unavailability-modal .modal-body").scrollTop($("#add-instructors-unavailability-modal .modal-body").prop("scrollHeight"));
});
