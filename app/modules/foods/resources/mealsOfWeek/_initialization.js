let mealsOfWeek = []
let mealsOfWeekFiltered = []
const containerCards = $('.js-cards-meals')
$.ajax({
    url: "?r=foods/foodMenu/GetMealsOfWeek",
    type: "POST",
}).success(function (response) {
    mealsOfWeek = JSON.parse(DOMPurify.sanitize(response))
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
                return publicTargetFilter.some((i) => i === item.food_public_target_id);
            });
        }
    });

    containerCards.html('')
    renderMeals(mealsOfWeekFiltered)
})

function renderMeals(mealsParam) {

    let cards = []
    cards = mealsParam.monday.map((meal) => {
        return meal.meals_component.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 1);
        }, '')

    })
    containerCards.append(cards)
    cards = mealsParam.tuesday.map((meal) => {
        return meal.meals_component.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 2);
        }, '')

    })
    containerCards.append(cards)
    cards = mealsParam.wednesday.map((meal) => {
        return meal.meals_component.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 3);
        }, '')

    })
    containerCards.append(cards)
    cards = mealsParam.thursday.map((meal) => {
        return meal.meals_component.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 4);
        }, '')

    })
    containerCards.append(cards)
    cards = mealsParam.friday.map((meal) => {
        return meal.meals_component.reduce((accumulator, meal_component) => {
            return accumulator + createCard(meal_component, meal, 5);
        }, '')

    })
    containerCards.append(cards)
}


function createCard(meal_component, meal, dayMeal) {
    // console.log(meal_component, meal)
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
        return  item.amount + ' ' + item.food_name.replace(/,/g, '');
    })
    return `<div class="t-cards ${dayMeal != day ? "hide" : ""}" data-public-target="${meal.food_public_target_id}" data-turn="${turn}">
                <div class="t-cards-content">
                    <div class="row">
                        <div class="t-tag-primary clear-margin--left">
                            ${meal.food_meal_type_description}
                        </div>
                        <div class="t-tag-secundary">
                            ${turn
        }
                        </div>
                        <div class="t-tag-secundary">
                            ${meal.food_public_target_name}
                        </div>
                    </div>
                    <div class="t-cards-title">${meal_component.description}</div>
                    <div class="t-cards-text clear-margin--left">Ingredientes: ${igredients.join(', ')} </div>
                </div>
            </div>`
}