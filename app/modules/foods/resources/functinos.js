
    $(document).on("click", ".js-add-meal", function () {
        $(".js-add-meal-modal").modal("show");
    })

$(document).on("click", ".js-add-plate", function () {
    var idNextAccordion = $("#js-accordion .ui-accordion-header").length + 1;
    $.ajax({
        url: "?r=foods/foodMenu/plateAccordion",
        data:{
            id: idNextAccordion
        },
        type: "GET",
    }).success(function (response) {
        $("#js-accordion").append(DOMPurify.sanitize(response))
        
            $('#js-accordion').accordion("destroy");
           
            $( "#js-accordion" ).accordion({
                active: false,
                collapsible: true,
                icons: false,
            });

            $(".ui-accordion-header").off("keydown");

            var labels = document.querySelectorAll("#js-stopPropagation");
            labels.forEach(function(label) {
                label.addEventListener("click", function(event) {
                    event.stopPropagation();
                });
            });

            
        
    });
});
