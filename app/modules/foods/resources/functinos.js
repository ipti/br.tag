

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
            initializeSelect2()
            addTacoFood()

            $(".ui-accordion-header").off("keydown");

            var labels = document.querySelectorAll("#js-stopPropagation");
            labels.forEach(function(label) {
                label.addEventListener("click", function(event) {
                    event.stopPropagation();
                });
            });

            
        
    });
});

$(document).on("click", ".js-add-meal", function () {  
    $(".t-expansive-panel").toggleClass("expanded");
    $(".js-add-meal").text(
        $(".t-expansive-panel").hasClass("expanded") ? "Fechar Formulário" : "Adicionar Refeição"
    ); 

});

function  initializeSelect2() {
    $("select.js-inicializate-select2").select2("destroy");
    $('select.js-inicializate-select2').select2();
}
function addTacoFood() {
     $("select.js-taco-foods").on("change", function() {
        $.ajax({
            url: "?r=foods/foodMenu/getFood",
            data: {
                idFood: $(this).val()
            },
            type: "GET",
        }).success(function (response) {
            response = JSON.parse(response)
            let aaa =  `<tr>
                                <td>${response.name}</td>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>${response.pt}</td>
                                <td>${response.lip}</td>
                                <td>${response.cho}</td>
                                <td>${response.kcal}</td>
                              
                        </tr>` 
       
           console.log(aaa)

            $( `table[data-idAccordion="${$(this).attr('data-idAccordion')} tbody"]`).html(aaa)
        })
       
    });
}

