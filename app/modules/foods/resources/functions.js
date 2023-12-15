function  initializeSelect2() {
    $("select.js-initialize-select2").select2("destroy");
    $('select.js-initialize-select2').select2();
}

$(".js-save-menu").on("click", function () {
    const form = $('#food-menu-form')
    if(form[0].checkValidity()) {
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
            "wednesday" :{
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
        foodMenu.description = $('.js-menu-name').val()
        foodMenu.food_public_target = $('select.js-public-target').val()
        foodMenu.start_date = $('.js-start-date').val()
        foodMenu.final_date = $('.js-final-date').val()
        foodMenu.observation = $('.js-observation').val()

        //get meals
        foodMenu.sunday = getMealsByDay(0)
        foodMenu.monday = getMealsByDay(1)
        foodMenu.tuesday = getMealsByDay(2)
        foodMenu.wednesday = getMealsByDay(3)
        foodMenu.thursday = getMealsByDay(4)
        foodMenu.friday = getMealsByDay(5)
        foodMenu.saturday = getMealsByDay(6)

        // console.log(foodMenu)
        $.ajax({
            url: "?r=foods/foodMenu/create",
            data: {
                foodMenu: foodMenu
            },
            type: "POST",
        }).success(function (response) {
            window.location.href = "?r=foods/foodMenu/index";
        })

    } else {
        const erros = []
        form.find(':input[required]').each(function () {
            if (!this.validity.valid) {
                if(!erros.includes(this.name))
                erros.push(this.name)
            }
        });
         
        showErros(erros)
    } 


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
        meal.food_meal_type = $(mealAccordion).find('select.js-meal-type').val()


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
function showErros(erros) {
    const menuError = $('.js-menu-error')
    const message  = erros.reduce((accumulator, campo) => {
        return accumulator + `O campo ${campo} é obrigatório<br>`;
    }, '');
    menuError.html(message)
    menuError.removeClass('hide')
}