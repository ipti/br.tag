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