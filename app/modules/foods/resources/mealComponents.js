/* DateComponent */

$('.js-date').mask("99/99/9999");
$(".js-date").datepicker({
  locate: "pt-BR",
  format: "dd/mm/yyyy",
  autoclose: true,
  todayHighlight: true,
  allowInputToggle: true,
  disableTouchKeyboard: true,
  keyboardNavigation: false,
  orientation: "bottom left",
  clearBtn: true,
  maxViewMode: 2,
  startDate: "01/01/" + $(".school-year").val(),
  endDate: "31/12/" + $(".school-year").val()
}).on('changeDate', function (ev, indirect) {
  data.actions.addDays($(this).val())
  $(".js-add-meal").removeClass('hide')
})

const DateComponent = function () {

  const days = []
  const daysOfWeek = ["Domingo", "Segunda-freia", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"]

  function addDays(dayStart) {
    days.length = 0;
    const [day, month, year] = dayStart.split('/')
    const dateFormat = [year, month, day].join('-');
    const date = new Date(dateFormat)

    for (let i = 0; i < 7; i++) {
      date.setDate(date.getDate() + 1)
      if (date.getDay() != 0 && date.getDay() != 6) {
        let [day, month, year] = [date.getDate(), date.getMonth() + 1, date.getFullYear()]
        day = day < 10 ? '0' + day : day
        days.push({
          date: [day, month, year].join('/'),
          order: date.getDay()
        });
      }


    }

    render(days)
  }

  function render(days) {
    const container = $(".js-days-of-week-component");
    const template = days.reduce((html, day, index) => {
      const isActive = index === 0 ? "active" : "";
      return html +
        `<li class="t-tabs__item js-day-tab js-change-pagination ${isActive}" data-day-of-week=${day.order} >
          <div class="text-primary">${daysOfWeek[day.order]}</div>
          <div class="text-secondary">${day.date}</div>
        </li>`;
    }, `<ul class="t-tabs__list">`) +
      `<li class="t-tabs__item js-change-pagination">
        <div class="text-primary">Selecionar <br> outra data</div>
      </li>
      </ul>`;
    container.html(template)
  }

  return {
    actions: {
      addDays: addDays
    }
  }
}

const data = DateComponent();

$(document).on('input', '.js-date', function () {
  data.actions.addDays($(this).val())
})

/* PlateComponent */
const PlateComponent = function () {
  let idPlateAccordion = 0;
  let foods = []
  getFoods();


  function addPlate(idMealAccordion) {
    render(idMealAccordion)
    initializePlateAccordion(idMealAccordion)
  }
  function initializePlateAccordion(idMealAccordion) {
    const container = $(`.js-plate-accordion[data-id-accordion='${idMealAccordion}']`) 
    if(container.find('.ui-accordion-header').length > 1) {
      container.accordion("destroy");
    }
    
    container.accordion({
      active: idPlateAccordion-1,
      collapsible: true,
      icons: false,
    });
    $(".js-plate-accordion-header").off("keydown");
  }
  function render(idMealAccordion) {

    const container = $(`.ui-accordion-content[data-id-accordion='${idMealAccordion}'] .js-plate-accordion`) 
    let template = `
      <div class="ui-accordion-header js-plate-accordion-header" data-id-accordion='${idPlateAccordion}' data-meal-id='${idMealAccordion}'>
        <input type="text" class="t-accordion-input-header js-plate-name" autofocus="true" placeholder="Digite o nome do prato" />
        <label>
        <span class="fa fa-pencil"  id="js-stopPropagation"></span>
        </label>
        <div class="row js-ingredients-names"></div>
      </div>
      <div class="ui-accordion-content js-plate-accordion-content" data-id-accordion='${idPlateAccordion}' data-meal-id='${idMealAccordion}'>
        <div class="row">
          <div class="t-field-select column clearfix">
            <select class="t-field-select__input js-inicializate-select2 js-taco-foods">
                <option value="">Busque pelo Alimento (TACO)</option>
            </select>
            </div>
        </div>
        <table class="tag-table-secondary centralize js-add-line">
            <tr>
              <th>Nome</th>
              <th>unidade</th>
              <th>Medida</th>
              <th>Quantidade</th>
              <th>PT</th>
              <th>LIP</th>
              <th>CHO</th>
              <th>KCAL</th>
              <th></th>
            </tr>
          </table>
          <div class="row">
              <a class="t-button-icon-danger js-remove-plate">Remover Prato</a>
          </div>
      </div>
    `
    container.attr("data-id-accordion", idMealAccordion);
    container.append(template)
    removePlate(idPlateAccordion)
    // addListTacoFood(idPlateAccordion)
    idPlateAccordion++;
  }
  function removePlate(idPlateAccordion) {
  const button =  $(`.js-plate-accordion-content[data-id-accordion="${idPlateAccordion}"] .js-remove-plate`)

  button.on(
      "click", function (event) {
        $(`.js-plate-accordion-header[data-id-accordion="${idPlateAccordion}"], 
        .js-plate-accordion-content[data-id-accordion="${idPlateAccordion}"]`).remove()
      }
    )
  }
  function addListTacoFood(idPlateAccordion) {
    const select = $(`.js-plate-accordion-content[data-id-accordion="${idPlateAccordion}"] .js-taco-foods`)

    /* $.map(foods, function (name, id) {
      select.append($('<option>', {
          value: id,
          text: name
      }));
   }); */
   for (var chave in objeto) {
    if (objeto.hasOwnProperty(chave)) {
        // Cria um elemento option
        var option = document.createElement("option");
        
        option.value = chave;
        option.text = objeto[chave];
  
        select.add(option);
    }
}

  }
  function getFoods (){
    return new Promise(function(resolve, reject) {
      $.ajax({
          url: "?r=foods/foodMenu/getTacoFoods",
          type: "GET",
      }).done(function(response) {
          foods = response;
          console.log(foods)
          resolve(response);
      }).fail(function(error) {
          reject(error);
      });
      
  });
  }
  return {
    actions: {
      addPlate: addPlate,
    }
  }
}

/* MealsComponent */

const MealsComponent = function () {

  const plates = PlateComponent();
  let idMealAccordion = 0;

  function addMeal() {
    const dayOfWeek = $('.js-day-tab.active').attr("data-day-of-week");
    render(dayOfWeek)

    initializeMealsAccordion()
    initializeSelect2()
  }
  function initializeMealsAccordion() {
    $('.js-meals-component').accordion("destroy");
    $( ".js-meals-component" ).accordion({
      active: idMealAccordion-1,
      collapsible: true,
      icons: false,
    });
  }

  function changeVisibleMeals(tabActive) {
    $('.js-meals-accordion-header, .js-meals-accordion-content').each(function () {
      $(this).attr("data-day-of-week") != tabActive.attr('data-day-of-week') ? $(this).addClass('hide') : $(this).removeClass('hide')
    })
  }

  function render(mealDay) {
    const container = $(".js-meals-component");

    let template = `

    <div class="ui-accordion-header js-meals-accordion-header row" data-day-of-week="${mealDay}" data-id-accordion="${idMealAccordion}">
      <div class="column justify-content--start js-meal-type">
        turno da refeição
      </div>
      <div class="column justify-content--space-between">
        <span></span>
        <span class="t-icon-trash js-remove-meal" data-id-accordion="${idMealAccordion}"></span>
      </div>
    </div>
    <div class="ui-accordion-content js-meals-accordion-content" data-day-of-week="${mealDay}" data-id-accordion="${idMealAccordion}">
      <div class="row">
        <div class="t-field-text column">
          <label class="t-field-text__label--required">Hora da refeição *</label>
          <input type='text' class='t-field-text__input js-mealTime' />
        </div>
        <div class="t-field-select column">
            <label class='t-field-select__label--required'>Refeição *</label>
            <select name="meal" class="js-inicializate-select2 select-search-on t-field-select__input js-change-meal-name">
              <option value="">Selecione a refeição</option>
              <option value="0">Café da manhã</option>
              <option value="1">Almoço</option>
              <option value="2">Lanche</option>
              <option value="3">Jantar</option>
          </select>
        </div>
      </div>
      <div class="row">
					<div class="t-field-select column">
            <label class="t-field-select__label--required">Turno *</label>
            <select class="js-inicializate-select2 select-search-on t-field-select__input js-shift">
                <option value="">Selecione o turno</option>
                <option value="M">Manhã</option>
                <option value="T">Tarde</option>
                <option value="N">Noite</option>
            </select>          
					</div>
					<div class="column"></div>
			</div>
      <div class"row">
        <div class="column t-buttons-container">
          <a class="t-button-secondary js-add-plate">
            <span class="t-icon-start"></span>
            Adicionar Prato
          </a>
        </div>
      </div>
      <div class="js-plate-accordion"></div>
    </div>     
          `;

    container.append(template)

    changeMealsName(idMealAccordion)
    addPlateToMeal(idMealAccordion)
    idMealAccordion++;

  }

  function removeMeal(idAccordion) {
    $(`.ui-accordion-header[data-id-accordion="${idAccordion}"], .ui-accordion-content[data-id-accordion="${idAccordion}"]`).remove()
  }

  function changeMealsName(idAccordion) {
    const select = $(`.ui-accordion-content[data-id-accordion='${idAccordion}'] .js-change-meal-name`)
    const title = $(`.ui-accordion-header[data-id-accordion='${idAccordion}'] .js-meal-type`)
      select.on("change",  function (event) {
        title.text($(this).select2('data').text);
      })
  }
  function addPlateToMeal(idAccordion) {
    const button = $(`.ui-accordion-content[data-id-accordion='${idAccordion}'] .js-add-plate`)
    
    
    button.on("click", function (event) {
      plates.actions.addPlate(idAccordion)
      initializeMealsAccordion()
    })
  }
  return {
    actions: {
      addMeal: addMeal,
      changeVisibleMeals: changeVisibleMeals,
      removeMeal: removeMeal,
    }
  }
}

const meals = MealsComponent();


$(document).on("click", ".js-add-meal", function () {
  meals.actions.addMeal()
});

$(document).on("click", ".js-remove-meal", function () {
  meals.actions.removeMeal($(this).attr("data-id-accordion"))
});

$(document).on("click", '.js-change-pagination', function () {
  let tabActive = $(this)
  $('.js-change-pagination.active').each(function () {
    $(this).removeClass('active');
  });
  tabActive.addClass("active");

  meals.actions.changeVisibleMeals(tabActive)
})

