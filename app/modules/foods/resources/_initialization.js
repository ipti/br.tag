let url = new URL(window.location.href);
let menuId = url.searchParams.get('id');


$( "#js-accordion" ).accordion({
    active: false,
    collapsible: true,
    icons: false,
});

    $.ajax({
        url: "?r=foods/foodmenu/getPublicTarget",
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
           select.select2('val' ,menuUpdate.foodPublicTarget)
        }
    })

if(menuId)  {
    const name = $('.js-menu-name');
    const startDate = $('.js-start-date');
    const finalDate = $('.js-final-date');
    const week = $('.js-week');
    const observation = $('.js-observation');
    name.val(menuUpdate.description);

    startDate.val(menuUpdate.startDate);
    finalDate.val(menuUpdate.finalDate);
    week.val(menuUpdate.week);
    observation.val(menuUpdate.observation);

    //renderizando refeições

    //segunda
    menuUpdate.monday.map((e) => {
        let plates = []
        e.mealsComponent.forEach((mealComponent)=>{
             let foodIngredients = mealComponent.ingredients.map((foodIngredient) => {

               let food =  {
                id: idIgredientes,
                amount: foodIngredient.amount,
                foodIdFk: foodIngredient.foodIdFk,
                foodMeasureUnitId: foodIngredient.foodMeasureUnitId
            }
            idIgredientes++
            return food
            })
            plates.push({
                    description: mealComponent.description,
                    id: idplates,
                    foodIngredients: foodIngredients
            })
            idplates++
        })
        meals.push({
            id: idMeals,
            mealDay: 1,
            mealTime: e.time,
            mealTypeId: e.foodMealType,
            mealType: 'Turno da refeição',
            shift: e.turn,
            plates: plates
        })
        idMeals++

    })
    //terça
    menuUpdate.tuesday.map((e) => {
        let plates = []
        e.mealsComponent.forEach((mealComponent)=>{
             let foodIngredients = mealComponent.ingredients.map((foodIngredient) => {

                let food =  {
                    id: idIgredientes,
                    amount: foodIngredient.amount,
                    foodIdFk: foodIngredient.foodIdFk,
                    foodMeasureUnitId: foodIngredient.foodMeasureUnitId
                }
            idIgredientes++
            return food
            })
            plates.push({
                    description: mealComponent.description,
                    id: idplates,
                    foodIngredients: foodIngredients
            })
            idplates++
        })
        meals.push({
            id: idMeals,
            mealDay: 2,
            mealTime: e.time,
            mealTypeId: e.foodMealType,
            mealType: 'Turno da refeição',
            shift: e.turn,
            plates: plates
        })
        idMeals++

    })
    //Quarta
    menuUpdate.wednesday.map((e) => {
        let plates = []
        e.mealsComponent.forEach((mealComponent)=>{
             let foodIngredients = mealComponent.ingredients.map((foodIngredient) => {

                let food =  {
                    id: idIgredientes,
                    amount: foodIngredient.amount,
                    foodIdFk: foodIngredient.foodIdFk,
                    foodMeasureUnitId: foodIngredient.foodMeasureUnitId
                }
            idIgredientes++
            return food
            })
            plates.push({
                    description: mealComponent.description,
                    id: idplates,
                    foodIngredients: foodIngredients
            })
            idplates++
        })
        meals.push({
            id: idMeals,
            mealDay: 3,
            mealTime: e.time,
            mealTypeId: e.foodMealType,
            mealType: 'Turno da refeição',
            shift: e.turn,
            plates: plates
        })
        idMeals++

    })
    //Quinta
    menuUpdate.thursday.map((e) => {
        let plates = []
        e.mealsComponent.forEach((mealComponent)=>{
             let foodIngredients = mealComponent.ingredients.map((foodIngredient) => {

                let food =  {
                    id: idIgredientes,
                    amount: foodIngredient.amount,
                    foodIdFk: foodIngredient.foodIdFk,
                    foodMeasureUnitId: foodIngredient.foodMeasureUnitId
                }
            idIgredientes++
            return food
            })
            plates.push({
                    description: mealComponent.description,
                    id: idplates,
                    foodIngredients: foodIngredients
            })
            idplates++
        })
        meals.push({
            id: idMeals,
            mealDay: 4,
            mealTime: e.time,
            mealTypeId: e.foodMealType,
            mealType: 'Turno da refeição',
            shift: e.turn,
            plates: plates
        })
        idMeals++

    })
    //Sexta
    menuUpdate.friday.map((e) => {
        let plates = []
        e.mealsComponent.forEach((mealComponent)=>{
             let foodIngredients = mealComponent.ingredients.map((foodIngredient) => {

                let food =  {
                    id: idIgredientes,
                    amount: foodIngredient.amount,
                    foodIdFk: foodIngredient.foodIdFk,
                    foodMeasureUnitId: foodIngredient.foodMeasureUnitId
                }
            idIgredientes++
            return food
            })
            plates.push({
                    description: mealComponent.description,
                    id: idplates,
                    foodIngredients: foodIngredients
            })
            idplates++
        })
        meals.push({
            id: idMeals,
            mealDay: 5,
            mealTime: e.time,
            mealTypeId: e.foodMealType,
            mealType: 'Turno da refeição',
            shift: e.turn,
            plates: plates
        })
        idMeals++

    })
    const day = $('.js-day-tab.active').attr("data-day-of-week")
    meals.forEach((e) => {
        MealsComponent(e, day).actions.render();
    });
    $('.js-meals-component').accordion("destroy");
    $( ".js-meals-component" ).accordion({
      active: false,
      collapsible: true,
      icons: false,
    });
}
