
    $(document).on("click", ".js-add-meal", function () {
        $(".js-add-meal-modal").modal("show");
    })

$(document).on("click", ".js-add-plate", function () {
    $.ajax({
        url: "?r=foods/foodMenu/plateAccordion",
        type: "GET",
    }).success(function (response) {
        console.log(response)
        $("#js-accordion").append(DOMPurify.sanitize(response))
      
            $('#js-accordion').accordion("destroy");

            $( "#js-accordion" ).accordion({
                active: false,
                collapsible: true,
                icons: false,
            });
        
    });
});
