let url = new URL(window.location.href);
let menuId = url.searchParams.get('id');


$( "#js-accordion" ).accordion({
    active: false,
    collapsible: true,
    icons: false,
});

    $.ajax({
        url: "?r=foods/foodMenu/getPublicTarget",
        type: "GET",
    }).success(function(response) {
       const publicTarget = JSON.parse(response);
        const select = $(`select.js-public-target`)
        $.map(publicTarget, function (name, id) {
            select.append($('<option>', {
                value: id,
                text: name,
            }));
        });
        if(menuId){
           select.select2('val' ,menuUpdate.food_public_target)
        }
    })

if(menuId)  {
     console.log(menuUpdate)
    const name = $('.js-menu-name')
    const startDate = $('.js-start-date')
    const finalDate = $('.js-final-date')
    const observation = $('.js-observation')
    name.val(menuUpdate.description)
    startDate.val(menuUpdate.start_date)
    finalDate.val(menuUpdate.final_date)
    observation.val(menuUpdate.observation)

    //renderizando refeições

    //segunda
    menuUpdate.monday.map((e) => {
        let plates = []
        e.meals_component.forEach((mealComponent)=>{
             let foodIngredients = mealComponent.food_ingredients.map((foodIngredient) => {

               let food =  {
                id: idIgredientes,
                amount: foodIngredient.amount,
                food_id_fk: foodIngredient.food_id_fk,
                food_measure_unit_id: foodIngredient.food_measure_unit_id
            }
            idIgredientes++
            return food
            })
            plates.push({
                    description: mealComponent.description,
                    id: idplates,
                    food_ingredients: foodIngredients
            })
            idplates++
        })
        meals.push({
            id: idMeals,
            mealDay: 1,
            mealTime: e.time,
            mealTypeId: e.food_meal_type,
            mealType: 'Turno da refeição',
            shift: e.turn,
            plates: plates
        })
        idMeals++

    })
    //terça
    menuUpdate.tuesday.map((e) => {
        meals.push({
                mealDay: 2,
                mealTime: e.time,
                mealTypeId: e.food_meal_type,
                mealType: 'Turno da refeição',
                shift: e.turn,
                plates: []
        })
    })
    const day = $('.js-day-tab.active').attr("data-day-of-week")
    meals.map((e) => MealsComponent(e, day).actions.render())
    $('.js-meals-component').accordion("destroy");
    $( ".js-meals-component" ).accordion({
      active: false,
      collapsible: true,
      icons: false,
    });
}




