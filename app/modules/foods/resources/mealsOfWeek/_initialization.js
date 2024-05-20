let mealsOfWeek = []
let mealsOfWeekFiltered = []
let allCardsIngredientsStatus = [];
let id_meal_indentification = [];
const containerCards = $('.js-cards-meals')

$(document).ready(function (){
    $(function () {
        $("#accordion-meal-recommendation").accordion({
            active: false,
            collapsible: true,
            icons: false,
            heightStyle: "content",
            animate: 600
        });
    });
})

$.ajax({
    url: "?r=foods/foodmenu/GetMealsRecommendation",
    type: "POST",
}).success(function (response) {
    mealsOfWeek = JSON.parse(DOMPurify.sanitize(response))
    console.log('aqui tem tudo ->', mealsOfWeek);
    mealsOfWeekFiltered = mealsOfWeek;
    containerCards.html('')
    renderMeals(mealsOfWeek)
})


$(document).on("click", '.js-change-pagination', function () {

    let clicked = $(this)
    $('.js-change-pagination.active').removeClass('active');
    clicked.addClass("active");

    containerCards.html('')
    renderMeals(mealsOfWeekFiltered)
})


$(document).on("change", '.js-filter-turns, .js-filter-public-target', function (e) {
    const turnFilter = $(".js-filter-turns").select2("val");
    const publicTargetFilter = $(".js-filter-public-target").select2("val");
    mealsOfWeekFiltered = JSON.parse(JSON.stringify(mealsOfWeek));

    const days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];

    days.forEach((day) => {
        if (turnFilter.length > 0) {
            mealsOfWeekFiltered[day] = mealsOfWeekFiltered[day].filter((item) => {
                return turnFilter.some((i) => i === item.turn);
            });
        }

        if (publicTargetFilter.length > 0) {
            mealsOfWeekFiltered[day] = mealsOfWeekFiltered[day].filter((item) => {
                return publicTargetFilter.some((i) => i === item.foodPublicTargetId);
            });
        }
    });

    containerCards.html('')
    renderMeals(mealsOfWeekFiltered)
})


function renderMeals(mealsParam) {

    let cards = []
    cards = mealsParam.monday.map((meal) => {
        return meal.mealsComponent.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 1);
        }, '')

    })
    containerCards.append(cards)
    cards = mealsParam.tuesday.map((meal) => {
        return meal.mealsComponent.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 2);
        }, '')

    })

    containerCards.append(cards)
    cards = mealsParam.wednesday.map((meal) => {
        return meal.mealsComponent.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 3);
        }, '')

    })

    containerCards.append(cards)
    cards = mealsParam.thursday.map((meal) => {
        return meal.mealsComponent.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 4);
        }, '')

    })

    containerCards.append(cards)
    cards = mealsParam.friday.map((meal) => {
        return meal.mealsComponent.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 5);
        }, '')

    })
    containerCards.append(cards)
}




function createCard(meal_component, meal, dayMeal) {
    const day = $('.js-day-tab.active').attr("data-day-of-week")
    let turn = ""
    switch (meal.turn) {
        case 'M':
            turn = "Manhã";
            break;
        case 'T':
            turn = "Tarde";
            break;
        case 'N':
            turn = "Noite";
            break;
        default:
            turn = ""
    }
    igredients = meal_component.ingredients.map((item) => {

        return item.amount + ' ' + item.foodName.replace(/,/g, '');
    })

    const ingredientStatuses = meal_component.ingredients.map((item) => {
        return item.statusInventoryFood;
    });

    const hasMissingIngredients = ingredientStatuses.includes("Emfalta");

    const exclamationSpan = hasMissingIngredients ? `<span class="t-exclamation" style="color: rgba(210, 28, 28, 1); font-size: 22px;"></span>` : '';


    console.log(meal_component.id_meal_food);

    const returnMealsStatus = meal_component.ingredients.map((item) => {
        return {
            status: item.statusInventoryFood,
            id_food_alternative: item.id_food,
            id_meal_componet: item.id_meal_food,
            id_food: item.foodIdFk,
            itemReference: item.itemReference,
            foodName: item.foodName.replace(/,/g, '')
        };
    });

    allCardsIngredientsStatus.push(returnMealsStatus);

    // console.log('------>',returnMealsStatus)


    return `<div class="t-cards ${dayMeal != day ? "hide" : ""}"  style=" max-width: none;" data-public-target="${meal.foodPublicTargetId}" data-turn="${turn}">
                <div class="t-cards-content">
                    <div class="mobile-row wrap">
                        <div style="margin:5px;" class="t-tag-primary clear-margin--left">
                            ${meal.foodMealTypeDescription}
                        </div>
                        <div style="margin:5px;" class="t-tag-secundary">
                            ${turn}
                        </div>
                        <div style="margin:5px;" class="t-tag-secundary">
                            ${meal.foodPublicTargetName}
                        </div>
                    </div>
                    <div class="t-cards-title">
                        <div class="t-cards-container-custom">
                            ${exclamationSpan}
                            ${meal_component.description}
                        </div>
                    </div>
                    <div class="t-cards-text clear-margin--left">
                        Ingredientes: ${igredients.join(', ')}
                    </div>
                </div>
            </div>`;

}

$(".js-expansive-panel").on("click", function () {
    $(".t-expansive-panel").toggle("expanded");
})



$(document).on("click", '.t-cards-container-custom', function () {

    const cardIndex = $(this).closest('.t-cards').index();
    const clickedCardIngredientsStatus = allCardsIngredientsStatus[cardIndex];

    let modalContent = '';
    let hasMissingIngredients = false;

    let ingredientList = [];

    clickedCardIngredientsStatus.forEach((ingredient) => {
        ingredientList.push(ingredient.foodName);
        if (ingredient.status === "Emfalta") {
            hasMissingIngredients = true;
        }
    });

    modalContent += `<p> <span class = "span-text">Ingrediente: </span> ${ingredientList.join(', ')}</p>`;

    if (hasMissingIngredients) {
        modalContent += `<div class="content-information">
        <p class='text-information' style=" color: #E98305;">Essa refeição tem ingredientes faltantes</p>`;
        var textoAviso = "Parece que alguns ingredientes desta refeição está em falta ou __ com pouco estoque.";
        if (textoAviso.includes("__")) {
            textoAviso = textoAviso.replace("__", "<br>");
        }
        modalContent += `<p class='text-aviso'>${textoAviso}</p>
            </div>`;
        modalContent += `<h4 class='text-h4'> Itens Faltantes</h4>`;
        modalContent += `<hr class="linha-personalizada">`
        clickedCardIngredientsStatus.forEach((ingredient) => {
            if (ingredient.status === "Emfalta") {
                modalContent += `
                                 <p><span class="t-exclamation" style="color: rgba(210, 28, 28, 1); font-size: 22px;"></span> ${ingredient.foodName}</p>`;
                ingredient.itemReference.forEach((item) => {
                    modalContent += `<p class="semaforo-line"> Semaforo: ${item.semaforo} Mudar ingrediente</p>`;
                    // modalContent += `<p class="semaforo-line" style="color: ${semaforoColor};"> Semaforo: ${item.semaforo} Mudar ingrediente</p>`;
                    modalContent += `<div class = "recommendation-ingredient">
                        <p>Adicionar </p>
                        <a href="#" class="ingredient-link" data-item-nome="${item.item_nome}" data-item-codigo="${item.codigo}" data-item-id_meal="${ingredient.id_meal_componet}" data-item-id-food="${ingredient.id_food_alternative}">
                        ${item.item_nome}</a>
                        </div>
                        `;

                });
            }
        });
    } else {
        modalContent += `<div class="content-information">
                       <p class='text-information' style=" color: #28a138;">Essa refeição não tem ingredientes faltantes</p>`;
        var textoAviso = "Não há ingredientes desta refeição em falta ou com pouco estoque.";
        if (textoAviso.includes("ou")) {
            textoAviso = textoAviso.replace("ou", "<br>");
        }
        modalContent += `<p class='text-aviso'>${textoAviso}</p>
                    </div>`;

    }

    $('#js-status-modal .modal-x').html(modalContent);
    $('#js-status-modal').modal('show');
});

$(document).on("click", '.ingredient-link', function (e) {
    e.preventDefault();
    const itemNome = $(this).data('item-nome');
    const idFood = $(this).data('item-codigo');
    const idMealFood = $(this).data('item-id_meal');
    console.log('item_nome clicado:', itemNome);
    console.log('id_food:', idFood);
    console.log('id_meal_food:', idMealFood);
});

$(document).on("click", '.ingredient-link', function (e) {
    e.preventDefault();
    const itemNome = $(this).data('item-nome');
    const idFood = $(this).data('item-codigo');
    const idMealFood = $(this).data('item-id_meal');
    const idMealFoodComponent = $(this).data('item-id-food');

    console.log('item_nome clicado:', itemNome);
    console.log('id_food da comida recomendada:', idFood);
    console.log('id_meal_food do prato:', idMealFood);
    console.log('id_meal_food_subst da comida a ser substituida:', idMealFoodComponent);


    $.ajax({
        url: `?r=foods/foodMenu/UpdateFoodMeal&id=${idFood}&idMeal=${idMealFood}&idFoodSubst=${idMealFoodComponent}`,
        data: {
            id_meal: idMealFood, id_food: idFood, id_food_subst: idMealFoodComponent
        },
        type: "POST",
        success: function (response) {
            console.log(response);
            location.reload();
        }
    });

});
