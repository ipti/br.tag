$(".js-save-menu").on("click", function () {
    const form = $('#food-menu-form')
    const erros = []
    form.find(':input[required]').each(function () {
        if (!this.validity.valid && !this.classList.contains('js-ignore-validation')) {
            if (!erros.includes(this.name)) {
                erros.push(this.name);
            }
        }
    });

    if(erros.length === 0 ) {
        let foodMenu = {
            "description": "",
            "week": "",
            "food_public_target": "",
            "start_date": "",
            "final_date": "",
            "include_saturday": "",
            "stages": "",
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
        foodMenu.week = $('select.js-week').val()
        foodMenu.observation = $('.js-observation').val()
        foodMenu.include_saturday = $('.js-include-saturday').is(':checked') ? 1 : 0
        foodMenu.stages = $(".js-stage-select").select2("val");

        //get meals
        foodMenu.sunday = getMealsByDay(0)
        foodMenu.monday = getMealsByDay(1)
        foodMenu.tuesday = getMealsByDay(2)
        foodMenu.wednesday = getMealsByDay(3)
        foodMenu.thursday = getMealsByDay(4)
        foodMenu.friday = getMealsByDay(5)
        if($('.js-include-saturday').is(':checked')) {
            foodMenu.saturday = getMealsByDay(6)
        }

        // console.log(foodMenu)
        if(menuId)
        {
            $.ajax({
                url: `?r=foods/foodMenu/update&id=${menuId}`,
                data: {
                    foodMenu: foodMenu
                },
                type: "POST",
            }).done(function (response) {
                window.location.href = "?r=foods/foodmenu/index";
            })
         } else
        {
            $.ajax({
                url: "?r=foods/foodmenu/create",
                data: {
                    foodMenu: foodMenu
                },
                type: "POST",
            }).done(function (response) {
                window.location.href = "?r=foods/foodmenu/index";
            })
        }

    } else {
        const erros = [];
        const inputs = form.find(':input[required]');
        inputs.each(function () {
            if (!this.validity.valid) {
                const isQuantity = this.name === "Quantidade";
                const mealError = $(this).closest('.js-meals-accordion-content');
                const mealName = mealError.find("select.js-meal-type option:selected").text();
                const dayOfWeek = mealError.attr("data-day-of-week");

                const errorExists = erros.some(erro =>
                    erro.field === this.name && erro.day === dayOfWeek && erro.meal === mealName
                );

                if (!errorExists) {
                    if (isQuantity) {
                        const measure = $(this).closest('.js-food-ingredient').find('.js-measure select option:selected').attr('data-measure');
                        if (measure === 'u') {
                            erros.push({
                                field: this.name,
                                day: dayOfWeek,
                                meal: mealName || undefined
                            });
                        }
                    } else {
                        erros.push({
                            field: this.name,
                            day: dayOfWeek,
                            meal: mealName || undefined
                        });
                    }
                }
            }
        });
        showErros(erros)
    }
});

$('.js-include-saturday').on("change", function(){
    if($(this).is(':checked')) {
        $('.js-saturday').removeClass('hide')
    } else {
        $('.js-saturday').addClass('hide')
    }
})

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
            "measurement_for_unit": "",
            "amount_for_unit": "",
            "amount": "",
        }
        foodIngredient.food_id_fk = $(row).attr('data-idTaco')
        foodIngredient.food_measure_unit_id = $(row).find('.js-measure select').val()
        foodIngredient.amount = $(row).find('.js-unit input').val()

        if($(row).find('.js-measure select option:selected').text() == "unidade") {
            foodIngredient.amount_for_unit = $(row).find(".js-amount-for-unit").val()
            foodIngredient.measurement_for_unit = $(row).find(".js-measurement-for-unit").val()
        }

        foodIngredients.push(foodIngredient)
    })
    return foodIngredients
}
function showErros(erros) {
    const days = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"]
    const menuError = $('.js-menu-error')
    const message  = erros.reduce((accumulator, item) => {
        console.log(item)
         if(item.day != undefined &&  item.meal != undefined) {
            if(item.meal != "Selecione a refeição"){
                return accumulator + `O campo <b>${item.field}</b> na refeição <b>${item.meal}</b> na <b>${days[item.day]}</b> é obrigatório<br>`;
            }
            return accumulator + `O campo <b>${item.field}</b> em uma refeição <b>sem tipo selecionado</b> na <b>${days[item.day]}</b> é obrigatório<br>`
         }
        return accumulator + `O campo <b>${item.field.replace(/\[\]$/, '')}</b> é obrigatório<br>`;
    }, '');
    menuError.html(message)
    menuError.show()
    menuError.removeClass('hide')
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
}
