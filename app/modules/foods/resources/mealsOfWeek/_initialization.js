let mealsOfWeek = []
let mealsOfWeekFiltered = []
let allCardsIngredientsStatus = [];
let id_meal_indentification = [];
const containerCards = $('.js-cards-meals')

$.ajax({
    url: "?r=foods/foodmenu/GetMealsRecommendation",
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
    ingredients_meal = meal_component.ingredients.map((item) => {

        return item.amount + item.measurementUnit +' ' + item.foodName.replace(/,/g, '');
    })


    const ingredientStatuses = meal_component.ingredients.map((item) => {
        return item.statusInventoryFood;
    });

    const hasMissingIngredients = ingredientStatuses.includes("Emfalta");

    const exclamationSpan = hasMissingIngredients ? `<span class="t-exclamation" style="color: rgba(210, 28, 28, 1); font-size: 22px;"></span>` : '';


    const returnMealsStatus = meal_component.ingredients.map((item) => {
        return {
            status: item.statusInventoryFood,
            id_food_alternative: item.id_food,
            id_meal_componet: item.id_meal_food,
            id_food: item.foodIdFk,
            itemReference: item.itemReference,
            itemAmount: item.amount,
            itemType: item.measurementUnit,
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
                        Ingredientes: ${ingredients_meal.join(', ')}
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

    let modalBodyContent = '';

    if (hasMissingIngredients) {
        // Conteúdo para modal-x
        modalContent += `<div class="content-information">
        <p class='text-information' style=" color: #E98305;">Essa refeição tem ingredientes faltantes</p>`;

        var textoAviso = "Parece que alguns ingredientes desta refeição está em falta ou __ com pouco estoque.";
        if (textoAviso.includes("__")) {
            textoAviso = textoAviso.replace("__", "<br>");
        }

        modalContent += `<p class='text-aviso'>${textoAviso}</p>
            </div>`;
        //  Adicionando o texto (itens faltantes) e linha cinza
        modalContent += `<h4 class='text-h4'> Itens Faltantes</h4>`;
        modalContent += `<hr class="linha-personalizada">`

        $('#accordion-meal-recommendation').html(modalBodyContent);
        // Conteúdo para modal-body
        clickedCardIngredientsStatus.forEach((ingredient) => {
            if (ingredient.status === "Emfalta") {
                modalBodyContent += `

                <div class="ui-accordion-header justify-content--space-between style-acordeon" style="background-color: #fafafe;">
                    <div class="mobile-row align-items--center">
                        <p class="t-title" id="title-id"><span class="t-exclamation" style="color: rgba(210, 28, 28, 1); font-size: 22px;"></span> ${ingredient.foodName}</p>
                    </div>
                    <span class="t-icon-down_arrow accordion-arrow-icon"></span>
                </div>

                <div class="ui-accordion-content">
                    <div class = "amount-container">
                        <p>Quantidade total do item no prato: ${ingredient.itemAmount}${ingredient.itemType}</p>
                    </div>`;

                if (ingredient.itemReference && ingredient.itemReference.length > 0) {
                    let itemCount = 1;
                    ingredient.itemReference.forEach((item) => {
                        let semaforoColor;
                        switch (item.semaforo) {
                            case 'Verde':
                                semaforoColor = 'rgba(40, 161, 56, 1)';
                                break;
                            case 'Amarelo':
                                semaforoColor = 'rgba(233, 131, 5, 1)';
                                break;
                            case 'Vermelho':
                                semaforoColor = 'rgba(210, 28, 28, 1)';
                                break;
                            default:
                                semaforoColor = '';
                        }
                        modalBodyContent += `
                        <div class="container-semaforo">
                            <p class="semaforo-line">
                                <span class="semaforo-dot" style="background-color: ${semaforoColor};">${itemCount}</span>
                                Mudar ingrediente
                            </p>
                            <div class="recommendation-ingredient">
                                <p>Adicionar </p>
                                <a href="#" class="ingredient-link" data-item-nome="${item.item_nome}" data-item-codigo="${item.codigo}" data-item-id_meal="${ingredient.id_meal_componet}" data-item-id-food="${ingredient.id_food_alternative}">
                                    ${item.item_nome}
                                </a>
                                - Quantidade no estoque: ${item.amount}${item.measurementUnit}
                            </div>
                        </div>`;

                        itemCount++;
                    });
                } else {
                    modalBodyContent += '<p class="container-semaforo"><span style="background-color: rgba(210, 28, 28, 1);"></span> Não possui itens recomendados no estoque</p>';
                }

                modalBodyContent += `</div>`;
            }
        });
    } else {
        modalContent += `<div class="content-information">
                            <p class='text-information' style=" color: #28a138;">Essa refeição não tem ingredientes faltantes</p>`;
        var textoAviso = "Não há ingredientes desta refeição em falta ou __ com pouco estoque.";
        if (textoAviso.includes("__")) {
            textoAviso = textoAviso.replace("__", "<br>");
        }
        modalContent += `<p class='text-aviso'>${textoAviso}</p></div>`;
    }

    $('#js-status-modal .modal-x').html(modalContent); // Adiciona conteúdo à modal-x
    $('#accordion-meal-recommendation').html(modalBodyContent); // Adiciona conteúdo à modal-body

    if ($("#accordion-meal-recommendation").hasClass("ui-accordion")) {
        $("#accordion-meal-recommendation").accordion("destroy");
    }
    initAccordionIcons();
    $("#accordion-meal-recommendation").accordion({
        active: false,
        collapsible: true,
        icons: false,
        heightStyle: "content",
        animate: 600
    });
    $('#js-status-modal').modal('show');
});



$(document).ready(function () {

    if (sessionStorage.getItem('substituicaoSucesso')) {
        $('#info-alert').removeClass('hide').addClass('alert-success').html("Alimento(s) adicionado(s) ao estoque com sucesso.");
        sessionStorage.removeItem('substituicaoSucesso');
    }

    $(document).on("click", '.ingredient-link', function (e) {
        e.preventDefault();
        const itemNome = $(this).data('item-nome');
        const idFood = $(this).data('item-codigo');
        const idMealFood = $(this).data('item-id_meal');
        const idMealFoodComponent = $(this).data('item-id-food');
        $.ajax({
            url: `?r=foods/foodMenu/UpdateFoodMeal&id=${idFood}&idMeal=${idMealFood}&idFoodSubst=${idMealFoodComponent}`,
            data: {
                id_meal: idMealFood, id_food: idFood, id_food_subst: idMealFoodComponent
            },
            type: "POST",
            success: function (response) {
                console.log(response);
                sessionStorage.setItem('substituicaoSucesso', 'true');
                location.reload();
            }
        });
    });
});

function initAccordionIcons() {
    $(".ui-accordion-header").click(function (event) {
        $(".accordion-arrow-icon").removeClass("rotate");
        if (!$(this).hasClass("ui-accordion-header-active")) {
            $(this).find($(".accordion-arrow-icon")).addClass("rotate");
        }
    });
}
