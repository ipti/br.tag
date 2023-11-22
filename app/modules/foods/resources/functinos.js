function  initializeSelect2() {
    $("select.js-inicializate-select2").select2("destroy");
    $('select.js-inicializate-select2').select2();
}
function  initializeAccordion() {
    if($('#js-accordion').find('ui-accordion-header').length > 0){
        $('#js-accordion').accordion("destroy"); 
    }  
    $( "#js-accordion" ).accordion({
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
            response = JSON.parse(DOMPurify.sanitize(response))
            let line = createMealComponent(
                response, $(select).attr('data-idAccordion')
                );
            $(`table[data-idAccordion="${$(select).attr('data-idAccordion')}"] tbody`).append(line)
            initializeAccordion()

            $(select).val('');
            initializeSelect2();

            addIngrendientsName($(select).attr('data-idAccordion'), response.name);

            removeTacoFood()
            
        })
          
    });
}
function removeTacoFood() {
    $(".js-remove-taco-food").off(); 
    $('.js-remove-taco-food').on("click", function(){
        let closeButton = $(this)
        let idAccordion = closeButton.parent().attr('data-idAccordion')
        let name = closeButton.parent().find('.js-food-name').text()
        removeIngrendientsName(idAccordion, name)
        closeButton.parent().remove();
        initializeAccordion()
    })
}
function addIngrendientsName(idAccordion, name) {

    let oldIngrendientsName =  $(`.js-ingredients-names[data-idAccordion="${idAccordion}"]`)
    let ingredientsList = oldIngrendientsName.text().trim().split(', ')
    let firstNameNewIngredient = name.split(', ')[0]
    
    if(ingredientsList.indexOf(firstNameNewIngredient) === -1){
        ingredientsList[0] == "" ?  ingredientsList[0] = firstNameNewIngredient: ingredientsList.push(firstNameNewIngredient)
    }

    let newIngredientsName = ingredientsList.join(", ");
    oldIngrendientsName.html(newIngredientsName)

}
function removeIngrendientsName(idAccordion, name){

    let allSelectedIngredients = [] 
    let tdElements = $(`tr[data-idAccordion='${idAccordion}'] td.js-food-name`); 
    tdElements.each(function () {
        allSelectedIngredients.push($(this).text());
      });
    let firstNameIngredient = name.split(', ')[0];
    let count = allSelectedIngredients.reduce(function (acc, element) {
        return acc + (element.split(', ')[0] === firstNameIngredient);
      }, 0);

    if(count == 1){
        let oldIngrendientsName =  $(`.js-ingredients-names[data-idAccordion="${idAccordion}"]`)
        let ingredientsList = oldIngrendientsName.text().trim().split(', ')
        let newIngredientsName = ingredientsList.filter((ingredient) => ingredient != firstNameIngredient)
        oldIngrendientsName.html(newIngredientsName)
    }
}
function createMealComponent({
   id, name, pt, lip, cho, kcal
}, idAccordion){
    
   const line =  $(`<tr data-idTaco='${id}' data-idAccordion='${idAccordion}'></tr>`)
        .append(`<td class='js-food-name'>${name}</td>`)
        .append(`<td class='js-unit'><input class='t-field-text__input' type='text' style='width:50px !important'></td>`)
        .append(`<td class='js-measure'>
                <select class="js-inicializate-select2 t-field-select__input" style='width:100px'>
                <option value="1">Concha</option>
                <option value="2">Unidade</option>
                <option value="3">Copos</option>
                </select>
            </td>`)
        .append(`<td>3</td>`)
        .append(`<td>${pt}</td>`)
        .append(`<td>${lip}</td>`)
        .append(`<td>${cho}</td>`)
        .append(`<td>${kcal}</td>`)
        .append(`<td class='js-remove-taco-food'><span class='t-icon-close t-button-icon'><span></td>`)
    
    return line;
}
let mealsArray = [];

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
    mealsArray.push(meal)
})

$(document).on("click", ".js-remove-plate", function () {
    let idAccordion = $(this).attr('data-idAccordion')
    $(`.ui-accordion-header[data-idAccordion="${idAccordion}"], 
        .ui-accordion-content[data-idAccordion="${idAccordion}"]`).remove()
});