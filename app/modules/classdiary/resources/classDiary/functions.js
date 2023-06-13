
var widthWindow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
renderFrequencyElement(widthWindow)

/* window.addEventListener('resize', function() {
     widthWindow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    renderFrequencyElement(widthWindow)
    console.log(widthWindow)
}); */

function renderFrequencyElement(w) {
    const urlParams = new URLSearchParams(window.location.search);
    const classrom_fk = urlParams.get("classrom_fk")
    const stage_fk = urlParams.get("stage_fk")
    const discipline_fk = urlParams.get("discipline_fk")
    const url = w <= 640 ?  `RenderFrequencyElementMobile` : `RenderFrequencyElementDesktop`;
    $.ajax({
        url: `${window.location.host}?r=classdiary/default/${url}&classrom_fk=${classrom_fk}&stage_fk=${stage_fk}&discipline_fk=${discipline_fk}`,
        type: "GET",
        
    }).success(function (response) {
        $(".js-frequency-element").html(response)
    });  
}