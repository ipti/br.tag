
var widthWindow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
renderFrequencyElement(widthWindow)

/* window.addEventListener('resize', function() {
     widthWindow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    renderFrequencyElement(widthWindow)
    console.log(widthWindow)
}); */

function renderFrequencyElement(w) {

     const url = w <= 640 ?  `RenderFrequencyElementMobile` : `RenderFrequencyElementDesktop`;
     console.log(`${window.location.host}?r=classdiary/default/${url}`)
    $.ajax({
        url: `${window.location.host}?r=classdiary/default/${url}`,
        type: "GET",
    }).success(function (response) {
        $(".js-frequency-element").html(response)
    });  
}