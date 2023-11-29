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
        "description": "",
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

    console.log(foodMenu)
    
});

function getMealsByDay(day) {
    let meals = []
    $(`.js-meals-accordion-content[data-day-of-week='${day}']`).each((index, element) => {
        const mealAccordion = element;
        let meal = {
                "time": "",
                "sequence": "",
                "turn": "",
                "food_meal_type": "",
                "meals_component": []
        }
        meal.time = $(mealAccordion).find('.js-mealTime').val()
        meal.sequence = index
        meal.turn = $(mealAccordion).find('select.js-shift').val()
        meal.food_meal_type = $(mealAccordion).find('select.js-food-meal-type').val()


        // get meals components

        $(mealAccordion).find('.js-plate-accordion-content').each((index, element) => {
           const plateAccordion = element
           const idPlateAccordion = $(plateAccordion).attr('data-id-accordion')
           let meal_component = {
                "description": "",
                "food_ingredients": []
            }
            
            meal_component.description = $(mealAccordion).find(`.js-plate-accordion-header[data-id-accordion="${idPlateAccordion}"] .js-plate-name`).val()
            meal_component.food_ingredients = getFoodIngredients(idPlateAccordion)
            meal.meals_component.push(meal_component)
        })
        meals.push(meal)
    })
    return meals
}
function getFoodIngredients(idPlateAccordion) {
    const plateAccordion = $(`.js-plate-accordion-content[data-id-accordion='${idPlateAccordion}']`)
    let foodIngredients = []
    $(plateAccordion).find('.js-food-ingredient').each((index, element) => {
        const row = element
        let foodIngredient = {
            "food_id_fk": "",
            "food_measure_unit_id": "",
            "amount": "",
        }
        foodIngredient.food_id_fk = $(row).attr('data-idTaco')
        foodIngredient.food_measure_unit_id = $(row).find('.js-measure select').val()
        foodIngredient.amount = $(row).find('.js-unit input').val()
        foodIngredients.push(foodIngredient)
    })
    return foodIngredients
}