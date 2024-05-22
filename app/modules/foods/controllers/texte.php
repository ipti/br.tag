Observe esse codigo

clickedCardIngredientsStatus.forEach((ingredient) => {
            if (ingredient.status === "Emfalta") {
                modalBodyContent += `
                <div class="ui-accordion-header justify-content--space-between">
                <div class="mobile-row align-items--center">
                    <p class="t-title" id = "title-id"><span class="t-exclamation" style="color: rgba(210, 28, 28, 1); font-size: 22px;"></span> ${ingredient.foodName}</p>
                </div>
                <span class="t-icon-down_arrow accordion-arrow-icon"></span>
            </div>

            <div class="ui-accordion-content">`;

                if (ingredient.itemReference && ingredient.itemReference.length > 0) {
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

                        <div class = "container-semaforo">
                            <p class="semaforo-line">
                                <span class="semaforo-dot" style="background-color: ${semaforoColor};"></span>
                                Mudar ingrediente - cor: ${item.semaforo}
                            </p>
                            <div class="recommendation-ingredient">
                                <p>Adicionar </p>
                                <a href="#" class="ingredient-link" data-item-nome="${item.item_nome}" data-item-codigo="${item.codigo}" data-item-id_meal="${ingredient.id_meal_componet}" data-item-id-food="${ingredient.id_food_alternative}">
                                    ${item.item_nome}
                                </a>
                            </div>
                        </div>`;
                    });
                } else {
                    modalBodyContent += '<p class="container-semaforo"><span style="background-color: rgba(210, 28, 28, 1);"></span> Não possui itens recomendados no estoque</p>';
                }

                modalBodyContent += `</div>`;
            }
        });

Como fazer para colocar um numero dentro de cada um das bolas ou seja, 1, 2, 3, 4 e assim sucessivamente para cada um dos itens, isso dentro de

<span class="semaforo-dot" style="background-color: ${semaforoColor};"> // add aqui </span>
