function  initializeSelect2() {
    $("select.js-inicializate-select2").select2("destroy");
    $('select.js-inicializate-select2').select2();
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

$(".js-save-menu").on("click", function () {
    let foodMenu = {
        "name": "",
        "food_public_target": "",
        "start_date": "",
        "final_date": "",
        "sunday" :{
            "meals":[]
        },
        "monday" :{
            "meals":[]
        },
        "tuesday" :{
            "meals":[] 
        },
        "wednessday" :{
            "meals":[]
        },
        "thursday" :{
            "meals":[]
        },
        "friday" :{
            "meals":[]
        },
        "saturday" :{
            "meals":[]
        }
    }

    //get menu infos
    foodMenu.name = $('.js-menu-name').val()
    foodMenu.food_public_target = $('select.js-public-target').val()
    foodMenu.start_date = $('.js-start-date').val()
    foodMenu.final_date = data.actions.getLastDay()

    //get meals
    foodMenu.sunday = getMealsByDay(0)
    foodMenu.monday = getMealsByDay(1)
    foodMenu.tuesday = getMealsByDay(2)
    foodMenu.wednessday = getMealsByDay(3)
    foodMenu.thursday = getMealsByDay(4)
    foodMenu.friday = getMealsByDay(5)
    foodMenu.saturday = getMealsByDay(6)
    
});

function getMealsByDay(day) {
    let meals = []
    $(`.js-meals-accordion-content[data-day-of-week='${day}']`).each((index, element) => {
        let meal = {
                "time": "",
                "sequence": "",
                "turn": "",
                "food_meal_type": "",
                "meals_component": []
        }
        meal.time = $(element).find('.js-mealTime').val()
        meal.sequence = index
        meal.turn = $(element).find('select.js-shift').val()
        meal.food_meal_type = $(element).find('select.js-food-meal-type').val()
        getMealsComponent()
        meals.push(meal)
    })
    return meals
}
function getMealsComponent() {
    
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