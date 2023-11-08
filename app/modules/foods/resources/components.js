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

/* MealsComponent */

const MealsComponent = function () {

  function addMeal() {
    if ($(".ui-accordion-header").length > 0) {
      $('.js-meals-component').accordion("destroy");
    }

    render($('.js-day-tab.active').attr("data-day-of-week"))
  }

  function changeVisibleMeals(tabActive) {
    $('.ui-accordion-header, .ui-accordion-content').each(function () {
      $(this).attr("data-day-of-week") != tabActive.attr('data-day-of-week') ? $(this).addClass('hide') : $(this).removeClass('hide')
    })
  }

  function render(mealDay) {
    const container = $(".js-meals-component");
    let idNextAccordion = "d"+mealDay+"meal"+$('.ui-accordion-header').length
    console.log(idNextAccordion)
    let template = `

    <div class="ui-accordion-header row" data-day-of-week="${mealDay}" data-id-accordion="${idNextAccordion}">
      <div class="column justify-content--start">
        <input type="text" class='t-accordion-input-header' autofocus placeholder='Digite o nome da refeição' style='width:150px' />
        <label class="align-items--center" for="">
            <span class="fa fa-pencil"  id="js-stopPropagation"></span>
        </label>
      </div>
      <div class="column justify-content--space-between">
        nome dos pratos 
        <span class="t-icon-trash js-remove-meal" data-id-accordion="${idNextAccordion}"></span>
      </div>
    </div>
    <div class="ui-accordion-content" data-day-of-week="${mealDay}" data-id-accordion="${idNextAccordion}">
      <div class="row">
        <div class="t-field-text column">
          <label class="t-field-text__label--required">Hora da refeição *</label>
          <input type='text' class='t-field-text__input js-mealTime' />
        </div>
        <div class="t-field-select column">
            <label class='t-field-select__label--required'>Refeição *</label>
            <select name="meal" class="select-search-on t-field-select__input js-meal">
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
            <select class="select-search-on t-field-select__input js-shift">
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
    </div>     

          `;
  
    container.append(template)

    $(".js-meals-component").accordion({
      active: false,
      collapsible: true,
      icons: false,
    });
    $(".ui-accordion-header").off("keydown");
  }
  
  function removeMeal(idAccordion) {
    $(`.ui-accordion-header[data-id-accordion="${idAccordion}"], .ui-accordion-content[data-id-accordion="${idAccordion}"]`).remove()
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



$(document).on("click", '.js-change-pagination', function () {
  let tabActive = $(this)
  $('.js-change-pagination.active').each(function () {
    $(this).removeClass('active');
  });
  tabActive.addClass("active");

  meals.actions.changeVisibleMeals(tabActive)
})

$(document).on("click", ".js-remove-meal", function () {
  meals.actions.removeMeal($(this).attr("data-id-accordion"))
});