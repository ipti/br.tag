Nesse código abaixo:

let mealsOfWeek = []
let mealsOfWeekFiltered = []
let allCardsIngredientsStatus = [];
const containerCards = $('.js-cards-meals')


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
            id_food: item.foodIdFk,
            itemReference: item.itemReference,
            foodName: item.foodName.replace(/,/g, '')
        };
    });

    allCardsIngredientsStatus.push(returnMealsStatus);



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

    $(function () {
        $("#accordion-item-food").accordion({
            active: false,
            collapsible: true,
            icons: false,
            heightStyle: "content",
            animate: 600
        });
    });



    const cardIndex = $(this).closest('.t-cards').index();
    const clickedCardIngredientsStatus = allCardsIngredientsStatus[cardIndex];

    let modalContent = '';
    let hasMissingIngredients = false;

    clickedCardIngredientsStatus.forEach((ingredient) => {
        modalContent += `<p>Ingrediente: ${ingredient.foodName}, ${ingredient.id_food}</p>`;
        if (ingredient.status === "Emfalta") {
            hasMissingIngredients = true;
        }
    });

    if (hasMissingIngredients) {
        modalContent += "<p>Essa refeição tem ingredientes faltantes:</p>";

        clickedCardIngredientsStatus.forEach((ingredient) => {
            if (ingredient.status === "Emfalta") {
                modalContent += `<p>Ingrediente: ${ingredient.foodName}, Status: ${ingredient.status}, Id_food: ${ingredient.id_food}</p>`;
                ingredient.itemReference.forEach((item) => {
                    modalContent += `<p>Código: ${item.codigo}, Semaforo: ${item.semaforo}</p>`;
                    modalContent += `<a href="#" class="ingredient-link" data-item-nome="${item.item_nome}">${item.item_nome}</a>`;


                });
            }
        });
    } else {
        modalContent += "<p>Essa refeição não tem ingredientes faltantes</p>";
    }

    $('#js-status-modal .modal-x').html(modalContent);
    $('#js-status-modal').modal('show');
});

$(document).on("click", '.ingredient-link', function (e) {
    e.preventDefault();
    const itemNome = $(this).data('item-nome');
    const idMealFood = $(this).data('id-meal-food');
    console.log('item_nome clicado:', itemNome);
    console.log('id_meal_food:', idMealFood);
});


Como fazer para que o valores de meal_component.id_meal_food em console.log(meal_component.id_meal_food) seja adicionado nessa parte quando clicar no botão:
$(document).on("click", '.ingredient-link', function (e) {
    e.preventDefault();
    const itemNome = $(this).data('item-nome');
    const idMealFood = $(this).data('id-meal-food');
    console.log('item_nome clicado:', itemNome);
});

