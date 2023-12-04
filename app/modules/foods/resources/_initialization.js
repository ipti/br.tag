$( "#js-accordion" ).accordion({
    active: false,
    collapsible: true,
    icons: false,
});
$(document).ready(function() {
    $.ajax({
        url: "?r=foods/foodMenu/getPublicTarget",
        type: "GET",
    }).done(function(response) {
       const publicTarget = JSON.parse(response);
        const select = $(`select.js-public-target`)

        $.map(publicTarget, function (name, id) {
            select.append($('<option>', {
                value: id,
                text: name
            }));
        });
    })
})
