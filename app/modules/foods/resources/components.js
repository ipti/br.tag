function parseDOM(htmlString) {
  const wrapper = $('<div></div>');
  wrapper.append(htmlString);
  return wrapper;
}
function  initializeAccordion() {
  $('#js-accordion').accordion("destroy");
  $( "#js-accordion" ).accordion({
      active: false,
      collapsible: true,
      icons: false,
  });
}

const MenuComponent = function ({ day }) {
  const meals = [];

  function addMeal({ hour, type, turn }) {
    meals.push({ day, hour, type, turn, plates: [] });
    $(document).trigger("renderMenu");
  }

  function render() {
    const container = $(".js-meals-component");
    const mealsElements = meals.map((meal) => MealsComponent(meal).render(0));
    container.html(mealsElements);
    if($('#js-accordion-plate').find('ui-accordion-header').length > 0){
      $('#js-accordion-plate').accordion("destroy");
    }

    $( "#js-accordion-plate" ).accordion({
        active: false,
        collapsible: true,
        icons: false,
    });
    initializeAccordion()

    $(".ui-accordion-header").off("keydown");
  }

  $(document).on("renderMenu", function () {
    render();
  });

  return {
    actions: { addMeal },
    render: render
  }
}

/* MealsComponent */

const MealsComponent = function (meal) {

  function addPlate({ name }) {
    meal.plates.push({ name });

    $(document).trigger("renderMenu");
  }

  function render(idAccordion) {

    const idNextAccordion = "D" + meal.day + "M" + idAccordion

    const template = `
      <div class="ui-accordion-header row" data-day-of-week="${meal.day}" data-id-accordion="${idNextAccordion}">
        <div class="column justify-content--start js-meal-type" data-id-accordion="${idNextAccordion}">
          turno da refeição
        </div>
        <div class="column justify-content--space-between">
          <span></span>
          <span class="t-icon-trash js-remove-meal" data-id-accordion="${idNextAccordion}"></span>
        </div>
      </div>
      <div class="ui-accordion-content" data-day-of-week="${meal.day}" data-id-accordion="${idNextAccordion}">
        <div class="row">
          <div class="t-field-text column">
            <label class="t-field-text__label--required">Hora da refeição</label>
            <input type='text' class='t-field-text__input js-mealTime' value=${meal.hour} />
          </div>
          <div class="t-field-select column">
              <label class='t-field-select__label--required'>Refeição</label>
              <select name="meal" class="select-search-on t-field-select__input js-meal-select js-inicializate-select2 data-id-accordion="${idNextAccordion}">
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
              <label class="t-field-select__label--required">Turno</label>
              <select class="select-search-on t-field-select__input js-shift js-inicializate-select2">
                  <option value="">Selecione o turno</option>
                  <option value="M">Manhã</option>
                  <option value="T">Tarde</option>
                  <option value="N">Noite</option>
              </select>
            </div>
            <div class="column"></div>
        </div>
        <div class="row">
          <div class="column t-buttons-container">
            <a class="t-button-secondary js-add-plate">
              <span class="t-icon-start"></span>
              Adicionar Prato
            </a>
          </div>
        </div>
        <div class="row">
          <div id="js-accordion-plate" class="js-plate-component column"></div>
        </div>
      </div>
    `;
    const wrapper = parseDOM(template);

    const title = wrapper.find('.js-meal-type');
    const containerPlates = wrapper.find(".js-plate-component");

    const plateElements = meal.plates.map((plate) => PlatesComponent().render(containerPlates));
    wrapper.find(".js-plate-component").append(plateElements);

    wrapper.find(`.js-meal-select`).on("change", function (event) {
      title.text($(this).select2('data').text);
    });

    wrapper.find(".js-mealTime").on("change", (e) =>  { meal.hour = e.target.value });

    wrapper.find('.js-add-plate').on('click', () =>  addPlate({ name: "" }));

    return wrapper.children();
  }

  function removeMeal(idAccordion) {
    $(`.ui-accordion-header[data-id-accordion="${idAccordion}"], .ui-accordion-content[data-id-accordion="${idAccordion}"]`).remove()
  }

  return {
    actions: {
      addPlate: addPlate,
      removeMeal: removeMeal,
    },
    render: render
  }
}

/* PlatesComponent */

const PlatesComponent = function () {
  /* Actions */

  function render(domElement) {
    const template = `
    <div class="ui-accordion-header row">
      <div class="column justify-content--space-between align-items--center is-one-third">
        <input type="text" name="name" class="t-accordion-input-header" autofocus="true" placeholder="Digite o nome do prato">
        <label>
          <span class="fa fa-pencil"  id="js-stopPropagation"></span>
        </label>
      </div>
      <div class="class="column is-two-thirds" js-ingredients-names"></div>
    </div>

    <div class="ui-accordion-content">
      <div class="row">
          <div class="t-field-select column clearfix">
            <select name="TACO" class="t-field-select__input">
              <option value="">Busque pelo Alimento (TACO)</option>
            </select>
          </div>
      </div>
      <table class="tag-table-secondary centralize js-add-line" data-idAccordion='<?= $idAccordion?>'>
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
          <a class="t-button-icon-danger js-remove-plate" data-idAccordion='<?= $idAccordion?>'>Remover Prato</a>
        </div>
    </div>
    `;

    const wrapper = parseDOM(template);
    return wrapper.children();
  }

  return {
    render: render
  };
}


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
  $(".js-show-meals-header").removeClass('hide')
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



const menu = MenuComponent(0);

$(document).on("click", ".js-add-meal", function () {
  menu.actions.addMeal({ hour: "", turn: "", type: "" });
});
