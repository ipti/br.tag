$(document).on("click", ".js-add-plate", function () {
    let idNextAccordion = $("#js-accordion .ui-accordion-header").length + 1;
    $.ajax({
        url: "?r=foods/foodMenu/plateAccordion",
        data:{
            id: idNextAccordion
        },
        type: "GET",
    }).success(function (response) {
        $("#js-accordion").append(DOMPurify.sanitize(response))
        
            initializeAccordion(idNextAccordion);
            initializeSelect2()
            addTacoFood()

            $(".ui-accordion-header").off("keydown");

            let labels = document.querySelectorAll("#js-stopPropagation");
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
function  initializeAccordion(idAccordion) {
    $('#js-accordion').accordion("destroy");      
    $( "#js-accordion" ).accordion({
        active: idAccordion-1,
        collapsible: true,
        icons: false,
    });
}
function addTacoFood() {
     $("select.js-taco-foods").on("change", function() {
        console.log($(this).val())
        let select = $(this)
        $.ajax({
            url: "?r=foods/foodMenu/getFood",
            data: {
                idFood: select.val()
            },
            type: "GET",
        }).success(function (response) {
    
            response = JSON.parse(response)
            let line = createMealComponent(
                response
                );
            $(`table[data-idAccordion="${$(select).attr('data-idAccordion')}"] tbody`).append(line)
            initializeAccordion($(select).attr('data-idAccordion'))
            $(select).val('');
            initializeSelect2()
        })
          
    });
}

function createMealComponent({
    name, pt, lip, cho, kcal
}){
    
   const line =  $("<tr></tr>")
        .append(`<td>${name}</td>`)
        .append(`<td>1</td>`)
        .append(`<td>2</td>`)
        .append(`<td>3</td>`)
        .append(`<td>${pt}</td>`)
        .append(`<td>${lip}</td>`)
        .append(`<td>${cho}</td>`)
        .append(`<td>${kcal}</td>`)
    
    return line;
}
let meals = [];
$(document).on("click", ".js-save-meal", function () {
    let meal = {
        mealTime: "",
        daysOfWeek: [],
        mealType: "",
        shift: "",
        plates: []
    }
    meal.mealTime =  $('.js-mealTime').val();
    meals.push(meal)
    console.log(meal)
})