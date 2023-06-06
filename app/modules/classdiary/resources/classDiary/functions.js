
var widthWindow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
renderFrequencyElement(widthWindow)

/* window.addEventListener('resize', function() {
     widthWindow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    renderFrequencyElement(widthWindow)
    console.log(widthWindow)
}); */

function renderFrequencyElement(w) {
    $.ajax({
        url: `${window.location.host}?r=classdiary/default/RenderFrequencyElement`,
        type: "POST",
        data: {
            widthWindow: w
        }
    }).success(function (response) {
        $(".js-frequency-element").html(response)
    });  
}