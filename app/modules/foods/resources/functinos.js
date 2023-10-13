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
    $("select.js-taco-foods").off(); 
     $("select.js-taco-foods").on("change", function() {
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
                response, $(select).attr('data-idAccordion')
                );
            $(`table[data-idAccordion="${$(select).attr('data-idAccordion')}"] tbody`).append(line)
            initializeAccordion($(select).attr('data-idAccordion'))

            $(select).val('');
            initializeSelect2();

            updateIgrendientsName($(select).attr('data-idAccordion'), response.name);

            removeTacoFood()
            
        })
          
    });
}
function removeTacoFood() {
    $(".js-remove-taco-food").off(); 
    $('.js-remove-taco-food').on("click", function(){
        let closeButton = $(this)
        let idAccordion = closeButton.parent().attr('data-idAccordion')
        closeButton.parent().remove();
        initializeAccordion(idAccordion)
    })
}
function updateIgrendientsName(idAccordion, name) {

    let oldIngrendientsName =  $(`.js-ingredients-names[data-idAccordion="${idAccordion}"]`)
    let ingredientsList = oldIngrendientsName.text().trim().split(', ')
    let firstNameNewIngredient = name.split(', ')[0]
    
    if(ingredientsList.indexOf(firstNameNewIngredient) === -1){
        ingredientsList[0] == "" ?  ingredientsList[0] = firstNameNewIngredient: ingredientsList.push(firstNameNewIngredient)
    }

    let newIngredientsName = ingredientsList.join(", ");
    oldIngrendientsName.html(newIngredientsName)

}
function createMealComponent({
   id, name, pt, lip, cho, kcal
}, idAccordion){
    
   const line =  $(`<tr data-idTaco='${id}' data-idAccordion='${idAccordion}'></tr>`)
        .append(`<td>${name}</td>`)
        .append(`<td class='js-unit'><input type='text'  style='width:50px'></td>`)
        .append(`<td class='js-measure'><input type='text'  style='width:100px'></td>`)
        .append(`<td>3</td>`)
        .append(`<td>${pt}</td>`)
        .append(`<td>${lip}</td>`)
        .append(`<td>${cho}</td>`)
        .append(`<td>${kcal}</td>`)
        .append(`<td class='js-remove-taco-food'><span class='t-icon-close t-button-icon'><span></td>`)
    
    return line;
}
let meals = [];

function getPlates() {
   let plates = $(".js-plate-accordion");
   let paltesResult = [];
   for (let index = 1; index <= plates.length; index++) {
    let plate = {
        name: $(`.js-plate-name[data-idAccordion="${index}"]`).val(),
        ingredients:[]
   }  
    $(`tr[data-idAccordion="${index}"]`).each(function() {
        let ingredient = {
            idTaco: $(this).attr('data-idTaco'),
            unit: $(this).find('td.js-unit input').val(),
            measure: $(this).find('td.js-measure input').val()
        }
        plate.ingredients.push(ingredient);
    });
    paltesResult.push(plate)
   }
   return paltesResult;
}

$(document).on("click", ".js-save-meal", function () {
    let meal = {
        mealTime: "",
        daysOfWeek: [

        ],
        mealType: "",
        shift: "",
        plates: []
    }
    meal.mealTime =  $('.js-mealTime').val();
    meal.mealType =  $('select.js-meal').val();
    meal.shift =  $('select.js-shift').val();

    ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(function(day) {
        meal.daysOfWeek[day] = $('#' + day).prop('checked');
    });
    meal.plates = console.log(getPlates())
    meals.push(meal)
    /* console.log(meal) */
})

